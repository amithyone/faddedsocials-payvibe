<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Deposit;
use App\Models\User;

class WebhookService
{
    /**
     * Send transaction notification to Xtrabusiness
     */
    public static function sendToXtrabusiness(Deposit $deposit, User $user, $status = 'successful')
    {
        try {
            // Xtrabusiness webhook configuration
            $webhookUrl = env('XTRABUSINESS_WEBHOOK_URL', 'https://xtrapay.cash/webhook');
            $apiKey = env('XTRABUSINESS_API_KEY', '');
            $apiCode = env('XTRABUSINESS_API_CODE', 'faddedsocials');

            if (empty($webhookUrl) || empty($apiKey)) {
                Log::warning('Xtrabusiness webhook not configured', [
                    'deposit_id' => $deposit->id,
                    'webhook_url' => $webhookUrl,
                    'has_api_key' => !empty($apiKey)
                ]);
                return false;
            }

            // Calculate the amount credited to user (amount after charges) - rounded to nearest 100
            $creditedAmount = max(0, $deposit->amount); // Ensure credited amount is never negative
            $creditedAmount = round($creditedAmount / 100) * 100; // Round to nearest 100
            $totalPaid = $deposit->final_amo ?? $deposit->amount + $deposit->charge;
            $charges = $deposit->charge ?? 0;

            // Prepare the webhook payload
            $payload = [
                'site_api_code' => $apiCode,
                'reference' => $deposit->trx,
                'amount' => $creditedAmount, // Amount credited to user
                'total_paid' => $totalPaid, // Total amount paid by user
                'charges' => $charges, // Transaction charges
                'currency' => 'NGN',
                'status' => $status === 'successful' ? 'success' : $status,
                'payment_method' => $deposit->gateway->code == 120 ? 'payvibe' : 'xtrapay',
                'customer_email' => $user->email,
                'customer_name' => $user->firstname . ' ' . $user->lastname,
                'description' => $deposit->gateway->code == 120 ? 'Deposit via PayVibe' : 'Deposit via Xtrapay',
                'external_id' => (string) $deposit->id,
                'metadata' => [
                    'deposit_id' => $deposit->id,
                    'user_id' => $user->id,
                    'credited_amount' => $creditedAmount, // Amount credited to user balance
                    'total_paid' => $totalPaid, // Total amount paid
                    'charges' => $charges, // Transaction charges
                    'final_amount' => $deposit->final_amo ?? null,
                    'charge' => $deposit->charge ?? null,
                    'payment_reference' => $deposit->trx,
                    'site_name' => 'faddedsocials.com',
                    'site_url' => 'https://faddedsocials.com',
                    'user_balance_before' => $user->balance - $creditedAmount, // User balance before credit
                    'user_balance_after' => $user->balance, // User balance after credit
                    'product_identifier' => env('PAYVIBE_PRODUCT_IDENTIFIER', 'socials')
                ],
                'timestamp' => $deposit->created_at ? $deposit->created_at->toISOString() : now()->toISOString()
            ];

            // Send webhook
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-API-Key' => $apiKey,
                'User-Agent' => 'Faddedsocials-Webhook/1.0'
            ])->timeout(30)->post($webhookUrl, $payload);

            // Log the response
            Log::info('Xtrabusiness webhook sent', [
                'deposit_id' => $deposit->id,
                'status_code' => $response->status(),
                'response' => $response->json(),
                'payload' => $payload
            ]);

            // Check if webhook was successful
            if ($response->successful()) {
                $responseData = $response->json();
                if (isset($responseData['success']) && $responseData['success']) {
                    Log::info('Xtrabusiness webhook successful', [
                        'deposit_id' => $deposit->id,
                        'message' => $responseData['message'] ?? 'Transaction processed'
                    ]);
                    return true;
                } else {
                    Log::error('Xtrabusiness webhook failed', [
                        'deposit_id' => $deposit->id,
                        'error' => $responseData['error'] ?? 'Unknown error'
                    ]);
                    return false;
                }
            } else {
                Log::error('Xtrabusiness webhook HTTP error', [
                    'deposit_id' => $deposit->id,
                    'status_code' => $response->status(),
                    'response' => $response->body()
                ]);
                return false;
            }

        } catch (\Exception $e) {
            Log::error('Xtrabusiness webhook exception', [
                'deposit_id' => $deposit->id ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Send transaction notification to PayVibe
     */
    public static function sendToPayVibe(Deposit $deposit, User $user, $status = 'successful')
    {
        try {
            // PayVibe webhook configuration
            $webhookUrl = env('PAYVIBE_WEBHOOK_URL', 'https://payvibeapi.six3tech.com/api/webhook');
            $apiKey = env('PAYVIBE_SECRET_KEY', '');
            $apiCode = env('PAYVIBE_API_CODE', 'faddedsocials');

            if (empty($webhookUrl) || empty($apiKey)) {
                Log::warning('PayVibe webhook not configured', [
                    'deposit_id' => $deposit->id,
                    'webhook_url' => $webhookUrl,
                    'has_api_key' => !empty($apiKey)
                ]);
                return false;
            }

            // Calculate the amount credited to user (amount after charges)
            $creditedAmount = $deposit->amount; // This is the amount that goes to user's balance
            $totalPaid = $deposit->final_amo ?? $deposit->amount + $deposit->charge;
            $charges = $deposit->charge ?? 0;

            // Prepare the webhook payload
            $payload = [
                'site_api_code' => $apiCode,
                'reference' => $deposit->trx,
                'amount' => $creditedAmount, // Amount credited to user
                'total_paid' => $totalPaid, // Total amount paid by user
                'charges' => $charges, // Transaction charges
                'currency' => 'NGN',
                'status' => $status === 'successful' ? 'success' : $status,
                'payment_method' => 'payvibe',
                'customer_email' => $user->email,
                'customer_name' => $user->firstname . ' ' . $user->lastname,
                'description' => 'Deposit via PayVibe',
                'external_id' => (string) $deposit->id,
                'metadata' => [
                    'deposit_id' => $deposit->id,
                    'user_id' => $user->id,
                    'credited_amount' => $creditedAmount, // Amount credited to user balance
                    'total_paid' => $totalPaid, // Total amount paid
                    'charges' => $charges, // Transaction charges
                    'final_amount' => $deposit->final_amo ?? null,
                    'charge' => $deposit->charge ?? null,
                    'payment_reference' => $deposit->trx,
                    'site_name' => 'faddedsocials.com',
                    'site_url' => 'https://faddedsocials.com',
                    'user_balance_before' => $user->balance - $creditedAmount, // User balance before credit
                    'user_balance_after' => $user->balance, // User balance after credit
                    'product_identifier' => env('PAYVIBE_PRODUCT_IDENTIFIER', 'socails')
                ],
                'timestamp' => $deposit->created_at ? $deposit->created_at->toISOString() : now()->toISOString()
            ];

            // Send webhook
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-API-Key' => $apiKey,
                'User-Agent' => 'Faddedsocials-Webhook/1.0'
            ])->timeout(30)->post($webhookUrl, $payload);

            // Log the response
            Log::info('PayVibe webhook sent', [
                'deposit_id' => $deposit->id,
                'status_code' => $response->status(),
                'response' => $response->json(),
                'payload' => $payload
            ]);

            // Check if webhook was successful
            if ($response->successful()) {
                $responseData = $response->json();
                if (isset($responseData['success']) && $responseData['success']) {
                    Log::info('PayVibe webhook successful', [
                        'deposit_id' => $deposit->id,
                        'message' => $responseData['message'] ?? 'Transaction processed'
                    ]);
                    return true;
                } else {
                    Log::error('PayVibe webhook failed', [
                        'deposit_id' => $deposit->id,
                        'error' => $responseData['error'] ?? 'Unknown error'
                    ]);
                    return false;
                }
            } else {
                Log::error('PayVibe webhook HTTP error', [
                    'deposit_id' => $deposit->id,
                    'status_code' => $response->status(),
                    'response' => $response->body()
                ]);
                return false;
            }

        } catch (\Exception $e) {
            Log::error('PayVibe webhook exception', [
                'deposit_id' => $deposit->id ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Send webhook for failed transactions
     */
    public static function sendFailedTransaction(Deposit $deposit, User $user, $reason = 'Transaction failed')
    {
        // Determine payment method and send appropriate webhook
        $paymentMethod = $deposit->gateway->code ?? '';
        
        if (str_contains(strtolower($paymentMethod), 'payvibe')) {
            return self::sendToPayVibe($deposit, $user, 'failed');
        }
        
        return self::sendToXtrabusiness($deposit, $user, 'failed');
    }

    /**
     * Send webhook for pending transactions (when deposit is created)
     */
    public static function sendPendingTransaction(Deposit $deposit, User $user)
    {
        // Determine payment method and send appropriate webhook
        $paymentMethod = $deposit->gateway->code ?? '';
        
        if (str_contains(strtolower($paymentMethod), 'payvibe')) {
            return self::sendToPayVibe($deposit, $user, 'pending');
        }
        
        return self::sendToXtrabusiness($deposit, $user, 'pending');
    }

    /**
     * Send webhook for successful transactions
     */
    public static function sendSuccessfulTransaction(Deposit $deposit, User $user)
    {
        // Determine payment method and send appropriate webhook
        $paymentMethod = $deposit->gateway->code ?? '';
        
        if (str_contains(strtolower($paymentMethod), 'payvibe')) {
            return self::sendToPayVibe($deposit, $user, 'success');
        }
        
        return self::sendToXtrabusiness($deposit, $user, 'success');
    }

    /**
     * Send credited amount notification to Xtrabusiness
     * This method specifically sends the amount credited to user's balance
     */
    public static function sendCreditedAmountToXtrabusiness(Deposit $deposit, User $user)
    {
        try {
            // Xtrabusiness webhook configuration
            $webhookUrl = env('XTRABUSINESS_WEBHOOK_URL', 'https://xtrapay.cash/webhook');
            $apiKey = env('XTRABUSINESS_API_KEY', '');
            $apiCode = env('XTRABUSINESS_API_CODE', 'faddedsocials');

            if (empty($webhookUrl) || empty($apiKey)) {
                Log::warning('Xtrabusiness webhook not configured for credited amount', [
                    'deposit_id' => $deposit->id,
                    'webhook_url' => $webhookUrl,
                    'has_api_key' => !empty($apiKey)
                ]);
                return false;
            }

            // Calculate the amount credited to user (amount after charges) - rounded to nearest 100
            $creditedAmount = max(0, $deposit->amount); // Ensure credited amount is never negative
            $creditedAmount = round($creditedAmount / 100) * 100; // Round to nearest 100
            $totalPaid = $deposit->final_amo ?? $deposit->amount + $deposit->charge;
            $charges = $deposit->charge ?? 0;

            // Prepare the webhook payload specifically for credited amount
            $payload = [
                'site_api_code' => $apiCode,
                'reference' => $deposit->trx,
                'amount' => $creditedAmount, // Amount credited to user (required field)
                'credited_amount' => $creditedAmount, // Amount credited to user
                'total_paid' => $totalPaid, // Total amount paid by user
                'charges' => $charges, // Transaction charges
                'currency' => 'NGN',
                'status' => 'success', // Use 'success' instead of 'credited'
                'payment_method' => $deposit->gateway->code == 120 ? 'payvibe' : 'xtrapay',
                'customer_email' => $user->email,
                'customer_name' => $user->firstname . ' ' . $user->lastname,
                'description' => 'Amount credited to user balance',
                'external_id' => (string) $deposit->id,
                'metadata' => [
                    'deposit_id' => $deposit->id,
                    'user_id' => $user->id,
                    'credited_amount' => $creditedAmount, // Amount credited to user balance
                    'total_paid' => $totalPaid, // Total amount paid
                    'charges' => $charges, // Transaction charges
                    'final_amount' => $deposit->final_amo ?? null,
                    'charge' => $deposit->charge ?? null,
                    'payment_reference' => $deposit->trx,
                    'site_name' => 'faddedsocials.com',
                    'site_url' => 'https://faddedsocials.com',
                    'user_balance_before' => $user->balance - $creditedAmount, // User balance before credit
                    'user_balance_after' => $user->balance, // User balance after credit
                    'credit_timestamp' => now()->toISOString(),
                    'transaction_type' => 'credit',
                    'product_identifier' => env('PAYVIBE_PRODUCT_IDENTIFIER', 'socials')
                ],
                'timestamp' => $deposit->created_at ? $deposit->created_at->toISOString() : now()->toISOString()
            ];

            // Send webhook
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-API-Key' => $apiKey,
                'User-Agent' => 'Faddedsocials-Webhook/1.0'
            ])->timeout(30)->post($webhookUrl, $payload);

            // Log the response
            Log::info('Xtrabusiness credited amount webhook sent', [
                'deposit_id' => $deposit->id,
                'credited_amount' => $creditedAmount,
                'status_code' => $response->status(),
                'response' => $response->json(),
                'payload' => $payload
            ]);

            // Check if webhook was successful
            if ($response->successful()) {
                $responseData = $response->json();
                if (isset($responseData['success']) && $responseData['success']) {
                    Log::info('Xtrabusiness credited amount webhook successful', [
                        'deposit_id' => $deposit->id,
                        'credited_amount' => $creditedAmount,
                        'message' => $responseData['message'] ?? 'Amount credited successfully'
                    ]);
                    return true;
                } else {
                    Log::error('Xtrabusiness credited amount webhook failed', [
                        'deposit_id' => $deposit->id,
                        'credited_amount' => $creditedAmount,
                        'error' => $responseData['error'] ?? 'Unknown error'
                    ]);
                    return false;
                }
            } else {
                Log::error('Xtrabusiness credited amount webhook HTTP error', [
                    'deposit_id' => $deposit->id,
                    'credited_amount' => $creditedAmount,
                    'status_code' => $response->status(),
                    'response' => $response->body()
                ]);
                return false;
            }

        } catch (\Exception $e) {
            Log::error('Xtrabusiness credited amount webhook exception', [
                'deposit_id' => $deposit->id ?? 'unknown',
                'credited_amount' => $creditedAmount ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Retry failed webhooks
     */
    public static function retryFailedWebhooks()
    {
        // This method can be used to retry failed webhook attempts
        // You can implement a queue system for this
        Log::info('Retrying failed webhooks');
    }
} 