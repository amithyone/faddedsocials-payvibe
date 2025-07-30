<?php

namespace App\Http\Controllers\Gateway\PayVibe;

use App\Constants\Status;
use App\Models\Deposit;
use App\Http\Controllers\Gateway\PaymentController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\WebhookService;

class ProcessController extends Controller
{
    public static function process(Deposit $deposit)
    {
        $gateWayCurrency = $deposit->gatewayCurrency();
        $payvibeAcc = json_decode($gateWayCurrency->gateway_parameter);
        
        try {
            // Ensure gateway parameter exists
            if (!$payvibeAcc || !isset($payvibeAcc->secret_key)) {
                throw new \Exception('Gateway parameter missing or invalid');
            }
        
            // Generate a unique 12-digit reference number
            do {
                $reference = substr(str_shuffle(time() . mt_rand(100000, 999999)), 0, 12);
                $exists = \App\Models\Deposit::where('trx', $reference)->exists();
            } while ($exists);
        
            // Update deposit transaction reference
            $deposit->trx = $reference;
            $deposit->charge = $gateWayCurrency->fixed_charge + (round($deposit->amount, 2)* ($gateWayCurrency->percent_charge /100));
            if($deposit->amount >= 10000){
                $deposit->charge = $gateWayCurrency->fixed_charge + (round($deposit->amount, 2)* (($gateWayCurrency->percent_charge + 0.5) /100));
            }
            $deposit->final_amo = round($deposit->amount + $deposit->charge, 0);
            $deposit->save();
        
            // API request using Bearer token
            $baseUrl = config('services.payvibe.base_url', 'https://payvibeapi.six3tech.com/api');
            $url = $baseUrl . '/v1/payments/virtual-accounts/initiate';
            $response = Http::withToken($payvibeAcc->secret_key ?? env('PAYVIBE_SECRET_KEY'))->post($url, [
                'reference' => $reference,
                // 'amount' => round($deposit->final_amo, 2), // Commented out as requested
                'product_identifier'=> env('PAYVIBE_PRODUCT_IDENTIFIER', 'socials')
            ]);
        
            if ($response->successful()) {
                $responseData = $response->json();
        
                if (isset($responseData['status']) && $responseData['status'] === true && isset($responseData['data'])) {
                    $accountData = $responseData['data'];
        
                    // Store virtual account details in deposit detail
                    $deposit->detail = [
                        'reference' => $accountData['reference'] ?? $reference,
                        'virtual_account' => $accountData['virtual_account_number'] ?? null,
                        'bank_name' => $accountData['bank_name'] ?? null,
                        'account_name' => $accountData['account_name'] ?? null
                    ];
                    $deposit->save();
        
                    $data = new \stdClass();
                    $data->val = [
                        'virtual_account' => $accountData['virtual_account_number'] ?? '',
                        'bank_name' => $accountData['bank_name'] ?? '',
                        'account_name' => $accountData['account_name'] ?? '',
                        'amount' => $deposit->final_amo, // Use deposit amount instead of API response
                        'currency' => $deposit->method_currency,
                        'reference' => $reference,
                        'custom' => $deposit->trx
                    ];
        
                    $data->view = 'user.payment.PayVibe';
        
                    return json_encode($data);
                } else {
                    return json_encode([
                        'error' => true,
                        'message' => 'Invalid response from API'
                    ]);
                }
            } else {
                return json_encode([
                    'error' => true,
                    'message' => 'Unable to generate virtual account'
                ]);
            }
        } catch (\Exception $e) {
            return json_encode([
                'error' => true,
                'message' => 'Something went wrong'
            ]);
        }
    }
    
    public function ipn(Request $request)
    {
        // Log the incoming request for debugging
        \Log::info('PayVibe IPN received', [
            'headers' => $request->headers->all(),
            'body' => $request->getContent(),
            'json' => $request->json()->all(),
            'all' => $request->all()
        ]);

        // Retrieve JSON payload
        $payload = $request->json()->all();
        
        // If no JSON payload, try form data
        if (empty($payload)) {
            $payload = $request->all();
        }
    
        // Log the payload structure
        \Log::info('PayVibe IPN payload structure', [
            'payload' => $payload,
            'has_data' => isset($payload['data']),
            'has_hash' => isset($payload['hash']),
            'keys' => array_keys($payload)
        ]);
    
        // Check for different possible payload structures
        $data = null;
        $hash = null;
        
        // Structure 1: PayVibe standard format {"reference": "...", "product_identifier": "..."}
        if (isset($payload['reference']) && isset($payload['product_identifier'])) {
            $data = $payload;
            $hash = $payload['hash'] ?? $payload['signature'] ?? null;
        }
        // Structure 2: {"data": {...}, "hash": "..."}
        elseif (isset($payload['data']) && isset($payload['hash'])) {
            $data = $payload['data'];
            $hash = $payload['hash'];
        }
        // Structure 3: Direct data without wrapper
        elseif (isset($payload['reference']) || isset($payload['status'])) {
            $data = $payload;
            $hash = $payload['hash'] ?? $payload['signature'] ?? null;
        }
        // Structure 4: Alternative field names
        elseif (isset($payload['ref']) || isset($payload['payment_status'])) {
            $data = $payload;
            $hash = $payload['hash'] ?? $payload['signature'] ?? null;
        }
        // Structure 5: Transaction ID format
        elseif (isset($payload['transaction_id'])) {
            $data = $payload;
            $hash = $payload['hash'] ?? $payload['signature'] ?? null;
        }
        // Unknown structure
        else {
            \Log::error('PayVibe IPN: Unknown payload structure', [
                'payload' => $payload
            ]);
            return response()->json(['error' => 'Invalid request structure'], 400);
        }
    
        // Retrieve secret key securely
        $accessKey = env('PAYVIBE_SECRET_KEY', 'your_default_secret_key');
    
        // Log hash verification attempt
        \Log::info('PayVibe IPN hash verification', [
            'data' => $data,
            'hash' => $hash,
            'has_access_key' => !empty($accessKey)
        ]);
    
        // Verify hash if provided
        if ($hash && $accessKey) {
            $computedHash = hash_hmac('sha256', json_encode($data), $accessKey);
            
            if (!hash_equals($computedHash, $hash)) {
                \Log::warning('PayVibe IPN: Hash verification failed', [
                    'expected' => $computedHash,
                    'received' => $hash
                ]);
                return $this->updateDepositInfo($data['reference'] ?? null, 'Invalid Authentication');
            }
        } else {
            \Log::info('PayVibe IPN: Skipping hash verification (no hash or key provided)');
        }
    
        // Extract transaction details
        $reference = $data['reference'] ?? $data['ref'] ?? $data['transaction_id'] ?? null;
        $productIdentifier = $data['product_identifier'] ?? null;
        $amountReceived = $data['amount'] ?? $data['amount_paid'] ?? $data['paid_amount'] ?? 0;
        $status = strtolower($data['status'] ?? $data['payment_status'] ?? 'successful'); // Default to successful for PayVibe
        
        \Log::info('PayVibe IPN: Extracted transaction details', [
            'reference' => $reference,
            'product_identifier' => $productIdentifier,
            'amount_received' => $amountReceived,
            'status' => $status,
            'data' => $data
        ]);
    
        // Validate reference
        if (!$reference) {
            \Log::error('PayVibe IPN: No reference found in payload', [
                'data' => $data
            ]);
            return response()->json(['error' => 'No reference provided'], 400);
        }
    
        // Define valid statuses
        $validStatuses = ['pending', 'successful', 'failed', 'reversed'];
    
        if (!in_array($status, $validStatuses)) {
            \Log::warning('PayVibe IPN: Invalid status received', [
                'status' => $status,
                'reference' => $reference
            ]);
            return $this->updateDepositInfo($reference, "Invalid status received: {$status}");
        }
        
        // Log product identifier for PayVibe
        if ($productIdentifier) {
            \Log::info('PayVibe IPN: Product identifier', [
                'product_identifier' => $productIdentifier,
                'reference' => $reference
            ]);
        }
    
        // Find deposit transaction with row locking
        $deposit = Deposit::where('trx', $reference)->lockForUpdate()->first();
    
        if (!$deposit) {
            return $this->updateDepositInfo($reference, 'Deposit not found');
        }
        
        if($deposit->status == 3){
            return response()->json(['message' => 'Transaction already rejected'], 200);
        }
        
        // Prevent multiple processing of successful transactions
        if ($deposit->status == 1 && $status == 'successful') {
            return response()->json(['message' => 'Transaction already processed'], 200);
        }
        $mismatch = false;
    
        // Validate received amount against the expected deposit amount
        if ((float) $amountReceived < (float) $deposit->final_amo) {
            $deposit->expected_amount = $deposit->final_amo;
            $this->updateDepositInfo($reference, "Amount mismatch: Expected {$deposit->final_amo}, received {$amountReceived}", $data);
            $mismatch = true;
            $deposit->final_amo = $amountReceived;
            $gateWayCurrency = $deposit->gatewayCurrency();
            $deposit->charge = $gateWayCurrency->fixed_charge + (round($amountReceived, 2)* ($gateWayCurrency->percent_charge /100));
            if($amountReceived >= 10000){
                $deposit->charge = $gateWayCurrency->fixed_charge + (round($amountReceived, 2)* (($gateWayCurrency->percent_charge + 0.5) /100));
            }
            $deposit->amount = max(0, $deposit->final_amo - $deposit->charge); // Ensure amount is never negative
            $deposit->amount = round($deposit->amount / 100) * 100; // Round to nearest 100
            $deposit->save();
        }
    
        // Start a database transaction
        DB::beginTransaction();
    
        try {
            if ($status === 'successful') {
                // Lock user record to prevent race conditions
                $user = User::where('id', $deposit->user_id)->lockForUpdate()->first();
    
                if ($user) {
                    // Update user balance
                    $user->increment('balance', $deposit->amount);
                }
    
                // Mark deposit as successful
                $deposit->update(['status' => 1]);
                
                // Send webhook for successful transaction
                WebhookService::sendSuccessfulTransaction($deposit, $user);
                
                // Send credited amount information to Xtrabusiness
                WebhookService::sendCreditedAmountToXtrabusiness($deposit, $user);
                
                if(!$mismatch){
                    $this->updateDepositInfo($reference, 'Transaction successful', $data);
                }
                
    
            } elseif ($status === 'failed' || $status === 'reversed') {
                // Mark deposit as failed/reversed
                $deposit->update(['status' => 3]);
                
                // Send webhook for failed transaction
                $user = User::find($deposit->user_id);
                WebhookService::sendFailedTransaction($deposit, $user, "Transaction {$status}");
                
                $this->updateDepositInfo($reference, "Transaction marked as {$status}", $data);
            }
    
            // Commit transaction
            DB::commit();
    
            return response()->json(['message' => 'Transaction Processed successfully'], 200);
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();
            return $this->updateDepositInfo($reference, 'Database error: ' . $e->getMessage());
        }
    }
    
    /**
     * Update the deposit detail JSON with additional info.
     *
     * @param string|null $reference
     * @param string $message
     * @param array|null $extraData
     * @return \Illuminate\Http\JsonResponse
     */
    private function updateDepositInfo($reference, $message, $extraData = null)
    {
        if (!$reference) {
            return response()->json(['error' => 'Transaction reference missing'], 400);
        }
    
        $deposit = Deposit::where('trx', $reference)->lockForUpdate()->first();
    
        if (!$deposit) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }
    
        // Decode existing details
        $detail = $deposit->detail;
    
        // Add new info
        $detail['info'] = $message;
        
        if ($extraData) {
            $detail['extra_data'] = $extraData;
        }
    
        // Update deposit record
        $deposit->update(['detail' => $detail]);
    
        return response()->json(['error' => $message], 400);
    }
    
    public function checkTransaction(Request $request, $reference)
    {
        // Validate input
        if (!$reference) {
            return response()->json(['error' => 'Reference number is required'], 400);
        }

        // PayVibe API URL
        $baseUrl = config('services.payvibe.base_url', 'https://payvibeapi.six3tech.com/api');
        $url = $baseUrl . "/v1/payments/virtual-accounts/requery/{$reference}";
        $accessKey = env('PAYVIBE_SECRET_KEY', 'your_default_secret_key');
        
        $response = Http::withToken($accessKey)->get($url);

        // Decode response
        $data = $response->json();

        // Check if request was successful
        if ($data['status'] == 'Successful' && isset($data['data']['payload'])) {
            $payload = $data['data']['payload'];

            // Send payload to internal endpoint
            $internalResponse = Http::post(env('APP_URL').'/ipn/payvibe', $payload);

            // Return the response from the internal endpoint
            return response()->json($internalResponse->json(), $internalResponse->status());
        }

        // If request failed, return the error response
        return response()->json($data, $response->status());
    }
    
    public function requery($reference)
    {
        // Define the API endpoint
        $url = env('APP_URL')."/ipn/payvibe/requery/{$reference}";
    
        try {
            // Make a GET request with Bearer token
            $response = Http::get($url);
    
            // Decode response
            $data = $response->json();
    
            // Check if request was successful (HTTP 200)
            if ($response->successful() && isset($data['data']['response']['message'])) {
                $message = $data['data']['response']['message'];
                
                
                // Redirect back with success message
                return redirect('/user/deposit/new')->with('message', $message);
            } else {
                // Handle error message from response
                $error = $data['message'] ?? 'Something went wrong. Please try again.';
                return redirect('/user/deposit/new')->with('error', $error);
            }
    
        } catch (\Exception $e) {
            // Catch request failures (network errors, timeouts, etc.)
            return redirect('/user/deposit/new')->with('error', 'Unable to process request.');
        }
    }
} 