<?php

namespace App\Http\Controllers\Gateway\Xtrapay;

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
        $xtrapayAcc = json_decode($gateWayCurrency->gateway_parameter);
        
        try {
            // Ensure gateway parameter exists
            if (!$xtrapayAcc || !isset($xtrapayAcc->access_key)) {
                throw new \Exception('Gateway parameter missing or invalid');
            }
        
            // Generate a unique 12-digit reference number
            do {
                $reference = substr(str_shuffle(time() . mt_rand(100000, 999999)), 0, 12);
                $exists = \App\Models\Deposit::where('trx', $reference)->exists();
            } while ($exists);
        
            // Update deposit transaction reference
            $deposit->trx = $reference;
            // Calculate charge: 1.5% + 100 for all amounts
            $deposit->charge = 100 + round($deposit->amount * 0.015, 2);
            $deposit->final_amo = round($deposit->amount + $deposit->charge, 0);
            $deposit->save();
        
            // API request using Bearer token
            $response = Http::withToken($xtrapayAcc->access_key)->post("https://mobile.xtrapay.ng/api/faddedsocials/generateAccount", [
                'reference' => $reference,
                'amount' => round($deposit->final_amo, 2),
                'service'=> 'fadded_social'
            ]);
        
            if ($response->successful()) {
                $responseData = $response->json();
        
                if (isset($responseData['statusCode']) && $responseData['statusCode'] == 200 && isset($responseData['data'])) {
                    $accountData = $responseData['data'];
        
                    // Store virtual account details in deposit detail
                    $deposit->detail = [
                        'reference' => $accountData['reference'] ?? $reference,
                        'virtual_account' => $accountData['accountNumber'] ?? null,
                        'bank_name' => $accountData['bank'] ?? null,
                        'account_name' => $accountData['accountName'] ?? null
                    ];
                    $deposit->save();
        
                    $data = new \stdClass();
                    $data->val = [
                        'virtual_account' => $accountData['accountNumber'] ?? '',
                        'bank_name' => $accountData['bank'] ?? '',
                        'account_name' => $accountData['accountName'] ?? '',
                        'amount' => $accountData['amount'],
                        'currency' => $deposit->method_currency,
                        'reference' => $reference,
                        'custom' => $deposit->trx
                    ];
        
                    $data->view = 'user.payment.Xtrapay';
        
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
        // Retrieve JSON payload
        $payload = $request->json()->all();
    
        // Ensure required fields exist
        if (!isset($payload['data']) || !isset($payload['hash'])) {
            return response()->json(['error' => 'Invalid request'], 400);
        }
    
        // Retrieve access key securely
        $accessKey = env('XTRAPAY_ACCESS_KEY', 'your_default_access_key');
    
        // Compute expected hash
        $computedHash = hash_hmac('sha256', json_encode($payload['data']), $accessKey);
    
        // Verify hash
        
        if (!hash_equals($computedHash, $payload['hash'])) {
            return $this->updateDepositInfo($payload['data']['reference'] ?? null, 'Invalid Authentication');
        }
    
        // Extract transaction details
        $data = $payload['data'];
        $reference = $data['reference'] ?? null;
        $amountReceived = $data['amount'] ?? 0;
        $status = strtolower($data['status'] ?? 'pending'); // Normalize status
    
        // Define valid statuses
        $validStatuses = ['pending', 'successful', 'failed', 'reversed'];
    
        if (!in_array($status, $validStatuses)) {
            return $this->updateDepositInfo($reference, "Invalid status received: {$status}");
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
            // Calculate charge: 1.5% + 100 for all amounts
            $deposit->charge = 100 + round($amountReceived * 0.015, 2);
            $deposit->amount = $deposit->final_amo - $deposit->charge;
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
        #$reference = $request->query('reference');

        // Validate input
        if (!$reference) {
            return response()->json(['error' => 'Reference number is required'], 400);
        }

        // XtraPay API URL
        $url = "https://mobile.xtrapay.ng/api/faddedsocials/requeryTransaction/{$reference}";
        $accessKey = env('XTRAPAY_ACCESS_KEY', 'your_default_access_key');
        
        $response = Http::withToken($accessKey)->get($url);

        // Decode response
        $data = $response->json();

        // Check if request was successful
        if ($data['status'] == 'Successful' && isset($data['data']['payload'])) {
            $payload = $data['data']['payload'];

            // Send payload to internal endpoint
            $internalResponse = Http::post(env('APP_URL').'/ipn/xtrapay', $payload);

            // Return the response from the internal endpoint
            return response()->json($internalResponse->json(), $internalResponse->status());
        }

        // If request failed, return the error response
        return response()->json($data, $response->status());
    }
    
    public function requery($reference)
    {
        // Define the API endpoint
        $url = env('APP_URL')."/ipn/xtrapay/requery/{$reference}";
    
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

