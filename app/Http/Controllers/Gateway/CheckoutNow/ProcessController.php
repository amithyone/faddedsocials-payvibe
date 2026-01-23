<?php

namespace App\Http\Controllers\Gateway\CheckoutNow;

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
use Illuminate\Support\Facades\Log;

class ProcessController extends Controller
{
    public static function process(Deposit $deposit)
    {
        $gateWayCurrency = $deposit->gatewayCurrency();
        $checkoutNowAcc = json_decode($gateWayCurrency->gateway_parameter);
        
        try {
            // Ensure gateway parameter exists
            if (!$checkoutNowAcc || !isset($checkoutNowAcc->api_key)) {
                throw new \Exception('Gateway parameter missing or invalid');
            }
        
            // Generate a unique transaction ID if not already set
            if (!$deposit->trx) {
                $deposit->trx = 'TXN-' . time() . '-' . mt_rand(100000, 999999);
            }
            
            // Charges are already calculated in PaymentController, just ensure final_amo is set
            if (!$deposit->final_amo) {
                $deposit->final_amo = round($deposit->amount + $deposit->charge, 2);
            }
            $deposit->save();
        
            // Get user details
            $user = User::find($deposit->user_id);
            if (!$user) {
                throw new \Exception('User not found');
            }
        
            // Get payer name from deposit detail (if provided by user) or use user's name
            $payerName = null;
            if ($deposit->detail && is_array($deposit->detail) && isset($deposit->detail['payer_name'])) {
                $payerName = $deposit->detail['payer_name'];
            } else {
                $payerName = trim($user->firstname . ' ' . $user->lastname);
            }
            
            // Validate payer name is not empty
            if (empty($payerName)) {
                throw new \Exception('Payer name is required. Please provide your name.');
            }
        
            // API request to CheckoutPay
            $baseUrl = config('services.checkoutnow.base_url', 'https://check-outpay.com/api/v1');
            $url = $baseUrl . '/payment-request';
            
            // Prepare request data
            $requestData = [
                'amount' => $deposit->final_amo,
                'payer_name' => $payerName,
                'webhook_url' => config('services.checkoutnow.webhook_url', env('APP_URL') . '/ipn/checkoutnow'),
                'service' => 'Wallet Deposit',
                'transaction_id' => $deposit->trx,
                'website_url' => env('APP_URL', 'https://faddedsocials.com')
            ];
            
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-API-Key' => $checkoutNowAcc->api_key ?? env('CHECKOUTNOW_API_KEY')
            ])->post($url, $requestData);
        
            if ($response->successful()) {
                $responseData = $response->json();
        
                if (isset($responseData['success']) && $responseData['success'] === true && isset($responseData['data'])) {
                    $paymentData = $responseData['data'];
        
                    // Store payment details in deposit detail
                    $deposit->detail = [
                        'transaction_id' => $paymentData['transaction_id'] ?? $deposit->trx,
                        'account_number' => $paymentData['account_number'] ?? null,
                        'account_name' => $paymentData['account_name'] ?? null,
                        'bank_name' => $paymentData['bank_name'] ?? null,
                        'status' => $paymentData['status'] ?? 'pending',
                        'expires_at' => $paymentData['expires_at'] ?? null,
                        'charges' => $paymentData['charges'] ?? null
                    ];
                    $deposit->save();
        
                    $data = new \stdClass();
                    $data->val = [
                        'account_number' => $paymentData['account_number'] ?? '',
                        'account_name' => $paymentData['account_name'] ?? '',
                        'bank_name' => $paymentData['bank_name'] ?? '',
                        'amount' => $deposit->final_amo,
                        'currency' => $deposit->method_currency,
                        'transaction_id' => $deposit->trx,
                        'expires_at' => $paymentData['expires_at'] ?? null,
                        'charges' => $paymentData['charges'] ?? null
                    ];
        
                    $data->view = 'user.payment.CheckoutNow';
        
                    return json_encode($data);
                } else {
                    Log::error('CheckoutNow: Invalid response from API', [
                        'response' => $responseData,
                        'deposit_id' => $deposit->id
                    ]);
                    return json_encode([
                        'error' => true,
                        'message' => $responseData['message'] ?? 'Invalid response from API'
                    ]);
                }
            } else {
                $errorResponse = $response->json();
                Log::error('CheckoutNow: API request failed', [
                    'status' => $response->status(),
                    'response' => $errorResponse,
                    'deposit_id' => $deposit->id
                ]);
                return json_encode([
                    'error' => true,
                    'message' => $errorResponse['message'] ?? 'Unable to create payment request'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('CheckoutNow: Exception in process', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'deposit_id' => $deposit->id ?? null
            ]);
            return json_encode([
                'error' => true,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ]);
        }
    }
    
    public function ipn(Request $request)
    {
        // Log the incoming request for debugging
        Log::info('CheckoutNow IPN received', [
            'headers' => $request->headers->all(),
            'body' => $request->getContent(),
            'json' => $request->json()->all(),
            'all' => $request->all()
        ]);

        // Verify API Key header (optional for CheckoutNow webhooks)
        $apiKey = $request->header('X-API-Key');
        
        if ($apiKey) {
            $expectedApiKey = env('CHECKOUTNOW_API_KEY');
            if ($expectedApiKey && !hash_equals($expectedApiKey, $apiKey)) {
                Log::warning('CheckoutNow IPN: Invalid API key', [
                    'expected_key' => substr($expectedApiKey, 0, 10) . '...',
                    'received_key' => substr($apiKey, 0, 10) . '...'
                ]);
                return response()->json(['error' => 'Invalid API key'], 401);
            }
        }

        // Retrieve JSON payload
        $payload = $request->json()->all();
        
        // If no JSON payload, try form data
        if (empty($payload)) {
            $payload = $request->all();
        }
    
        // Log the payload structure
        Log::info('CheckoutNow IPN payload structure', [
            'payload' => $payload,
            'event' => $payload['event'] ?? null,
            'transaction_id' => $payload['transaction_id'] ?? null
        ]);
    
        // Check for payment.approved event
        $event = $payload['event'] ?? null;
        $transactionId = $payload['transaction_id'] ?? null;
        $status = $payload['status'] ?? null;
        
        if (!$transactionId) {
            Log::error('CheckoutNow IPN: No transaction ID found in payload', [
                'payload' => $payload
            ]);
            return response()->json(['error' => 'No transaction ID provided'], 400);
        }
    
        // Find deposit transaction with row locking
        $deposit = Deposit::where('trx', $transactionId)->lockForUpdate()->first();
    
        if (!$deposit) {
            Log::error('CheckoutNow IPN: Deposit not found', [
                'transaction_id' => $transactionId,
                'searched_field' => 'trx',
                'payload_keys' => array_keys($payload)
            ]);
            return response()->json(['error' => 'Deposit not found'], 404);
        }
        
        Log::info('CheckoutNow IPN: Deposit found', [
            'deposit_id' => $deposit->id,
            'transaction_id' => $transactionId,
            'current_status' => $deposit->status,
            'expected_status' => $status,
            'event' => $event,
            'amount' => $deposit->amount,
            'final_amo' => $deposit->final_amo
        ]);
        
        if($deposit->status == 3){
            Log::info('CheckoutNow IPN: Transaction already rejected', [
                'deposit_id' => $deposit->id,
                'transaction_id' => $transactionId
            ]);
            return response()->json(['message' => 'Transaction already rejected'], 200);
        }
        
        // Prevent multiple processing of successful transactions
        if ($deposit->status == 1 && $status === 'approved') {
            Log::info('CheckoutNow IPN: Transaction already processed', [
                'deposit_id' => $deposit->id,
                'transaction_id' => $transactionId
            ]);
            return response()->json(['message' => 'Transaction already processed'], 200);
        }
    
        // Handle payment.approved event
        if ($event === 'payment.approved' && $status === 'approved') {
            // Extract payment details
            $amount = $payload['amount'] ?? $deposit->final_amo;
            $receivedAmount = $payload['received_amount'] ?? $amount;
            $charges = $payload['charges'] ?? null;
            
            // Check for amount mismatch
            $mismatch = false;
            if (isset($payload['is_mismatch']) && $payload['is_mismatch']) {
                $mismatch = true;
                Log::warning('CheckoutNow IPN: Amount mismatch detected', [
                    'transaction_id' => $transactionId,
                    'expected' => $deposit->final_amo,
                    'received' => $receivedAmount,
                    'mismatch_reason' => $payload['mismatch_reason'] ?? null
                ]);
            }
            
            // Update deposit with received amount if different
            if (abs($receivedAmount - $deposit->final_amo) > 0.01) {
                // Adjust deposit amount based on what was actually received
                $deposit->amount = max(0, $receivedAmount - $deposit->charge);
                $mismatch = true;
            }
            
            // Update deposit detail with webhook data
            $detail = $deposit->detail ?? [];
            $detail['webhook_data'] = [
                'event' => $event,
                'status' => $status,
                'received_amount' => $receivedAmount,
                'is_mismatch' => $mismatch,
                'matched_at' => $payload['matched_at'] ?? null,
                'approved_at' => $payload['approved_at'] ?? null,
                'timestamp' => $payload['timestamp'] ?? now()->toISOString()
            ];
            $deposit->detail = $detail;
            $deposit->save();
        
            // Start a database transaction
            DB::beginTransaction();
        
            try {
                Log::info('CheckoutNow IPN: Processing successful transaction', [
                    'deposit_id' => $deposit->id,
                    'transaction_id' => $transactionId,
                    'amount_to_credit' => $deposit->amount,
                    'user_id' => $deposit->user_id,
                    'event' => $event,
                    'status' => $status,
                    'received_amount' => $receivedAmount,
                    'expected_amount' => $deposit->final_amo
                ]);
                
                // Lock user record to prevent race conditions
                $user = User::where('id', $deposit->user_id)->lockForUpdate()->first();
        
                if ($user) {
                    $balanceBefore = $user->balance;
                    // Update user balance - credit the deposit amount to user's balance
                    $user->increment('balance', $deposit->amount);
                    $balanceAfter = $user->fresh()->balance;
                    
                    Log::info('CheckoutNow IPN: User balance credited', [
                        'deposit_id' => $deposit->id,
                        'transaction_id' => $transactionId,
                        'user_id' => $user->id,
                        'amount_credited' => $deposit->amount,
                        'balance_before' => $balanceBefore,
                        'balance_after' => $balanceAfter
                    ]);
                } else {
                    Log::error('CheckoutNow IPN: User not found', [
                        'deposit_id' => $deposit->id,
                        'transaction_id' => $transactionId,
                        'user_id' => $deposit->user_id
                    ]);
                }
        
                // Mark deposit as successful
                $deposit->update(['status' => 1]);
                
                // Refresh deposit to ensure gateway relationship is loaded
                $deposit->refresh();
                $deposit->load('gateway');
                
                Log::info('CheckoutNow IPN: Sending webhooks', [
                    'deposit_id' => $deposit->id,
                    'transaction_id' => $transactionId,
                    'status' => $deposit->status
                ]);
                
                // Send webhook for successful transaction
                WebhookService::sendSuccessfulTransaction($deposit, $user);
                
                // Send credited amount information to Xtrabusiness
                WebhookService::sendCreditedAmountToXtrabusiness($deposit, $user);
                
                if(!$mismatch){
                    Log::info('CheckoutNow IPN: Transaction successful', [
                        'transaction_id' => $transactionId,
                        'amount' => $deposit->amount,
                        'deposit_id' => $deposit->id
                    ]);
                }
                
                Log::info('CheckoutNow IPN: Successfully processed and credited', [
                    'deposit_id' => $deposit->id,
                    'transaction_id' => $transactionId
                ]);
        
                // Commit transaction
                DB::commit();
                
                Log::info('CheckoutNow IPN: Transaction committed successfully', [
                    'transaction_id' => $transactionId,
                    'deposit_id' => $deposit->id,
                    'status' => $status
                ]);
        
                return response()->json(['message' => 'Transaction processed successfully'], 200);
            } catch (\Exception $e) {
                // Rollback transaction on error
                DB::rollBack();
                
                Log::error('CheckoutNow IPN: Database error', [
                    'transaction_id' => $transactionId,
                    'deposit_id' => $deposit->id ?? null,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                return response()->json(['error' => 'Database error: ' . $e->getMessage()], 500);
            }
        } elseif ($event === 'payment.rejected' || $status === 'rejected') {
            // Handle rejected payments
            $deposit->update(['status' => 3]);
            
            $user = User::find($deposit->user_id);
            WebhookService::sendFailedTransaction($deposit, $user, "Transaction rejected");
            
            Log::info('CheckoutNow IPN: Transaction rejected', [
                'transaction_id' => $transactionId
            ]);
            
            return response()->json(['message' => 'Transaction marked as rejected'], 200);
        } else {
            // Unknown event or status
            Log::warning('CheckoutNow IPN: Unknown event or status', [
                'transaction_id' => $transactionId,
                'event' => $event,
                'status' => $status
            ]);
            return response()->json(['message' => 'Event received but not processed'], 200);
        }
    }
    
    public function checkTransaction(Request $request, $transactionId)
    {
        // Validate input
        if (!$transactionId) {
            return response()->json(['error' => 'Transaction ID is required'], 400);
        }

        // CheckoutPay API URL
        $baseUrl = config('services.checkoutnow.base_url', 'https://check-outpay.com/api/v1');
        $url = $baseUrl . "/payment/{$transactionId}";
        $apiKey = env('CHECKOUTNOW_API_KEY');
        
        $response = Http::withHeaders([
            'X-API-Key' => $apiKey
        ])->get($url);

        // Decode response
        $data = $response->json();

        // Check if request was successful
        if ($response->successful() && isset($data['success']) && $data['success'] === true) {
            $paymentData = $data['data'] ?? [];
            
            // If payment is approved, trigger webhook processing
            if (isset($paymentData['status']) && $paymentData['status'] === 'approved') {
                // Simulate webhook payload
                $webhookPayload = [
                    'event' => 'payment.approved',
                    'transaction_id' => $transactionId,
                    'status' => 'approved',
                    'amount' => $paymentData['amount'] ?? null,
                    'received_amount' => $paymentData['amount'] ?? null,
                    'charges' => $paymentData['charges'] ?? null,
                    'approved_at' => $paymentData['approved_at'] ?? null,
                    'timestamp' => now()->toISOString()
                ];
                
                // Send to internal endpoint
                $internalResponse = Http::post(env('APP_URL').'/ipn/checkoutnow', $webhookPayload);
                
                return response()->json($internalResponse->json(), $internalResponse->status());
            }
            
            return response()->json($data, 200);
        }

        // If request failed, return the error response
        return response()->json($data, $response->status());
    }
}
