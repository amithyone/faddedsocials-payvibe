<?php

namespace App\Http\Controllers\Gateway\Payaza;

use App\Constants\Status;
use App\Models\Deposit;
use App\Http\Controllers\Gateway\PaymentController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProcessController extends Controller
{
    public static function process($deposit)
    {
        try {
            Log::info('Payaza Process Started:', ['deposit_id' => $deposit->id]);
            
            $payazaAcc = json_decode($deposit->gatewayCurrency()->gateway_parameter);
            
            if (!$payazaAcc || !isset($payazaAcc->public_key)) {
                Log::error('Payaza Config Error:', ['gateway_parameter' => $deposit->gatewayCurrency()->gateway_parameter]);
                throw new \Exception('Invalid gateway configuration');
            }

            // Format amount to kobo (multiply by 100)
            $amount = round($deposit->final_amo * 100);

            $send = [
                'key' => trim($payazaAcc->public_key->value),
                'email' => auth()->user()->email,
                'amount' => $amount,
                'currency' => $deposit->method_currency,
                'ref' => $deposit->trx,
                'view' => 'user.payment.Payaza'
            ];

            Log::info('Payaza Process Data:', $send);
            return json_encode($send);

        } catch (\Exception $e) {
            Log::error('Payaza Process Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $send['error'] = true;
            $send['message'] = 'Something went wrong: ' . $e->getMessage();
            return json_encode($send);
        }
    }

    public function ipn()
    {
        try {
            $payload = file_get_contents('php://input');
            $data = json_decode($payload, true);

            Log::info('Payaza IPN Received:', $data ?? []);

            if (!$data) {
                Log::error('Payaza IPN: Invalid data received');
                return response('Invalid data', 400);
            }

            // Validate required fields
            $requiredFields = ['reference', 'amount', 'status'];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field])) {
                    Log::error('Payaza IPN: Missing field', ['field' => $field]);
                    return response('Missing field: ' . $field, 400);
                }
            }

            // Find the deposit
            $deposit = Deposit::where('trx', $data['reference'])->orderBy('id', 'DESC')->first();

            if (!$deposit) {
                Log::error('Payaza IPN: Deposit not found', ['reference' => $data['reference']]);
                return response('Deposit not found', 404);
            }

            // Get gateway parameters
            $payazaAcc = json_decode($deposit->gatewayCurrency()->gateway_parameter);
            
            if (!$payazaAcc || !isset($payazaAcc->secret_key)) {
                Log::error('Payaza IPN: Invalid gateway config', ['deposit_id' => $deposit->id]);
                return response('Invalid gateway configuration', 500);
            }

            // Verify transaction with Payaza
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . trim($payazaAcc->secret_key->value)
                ])->get('https://api.payaza.africa/api/v1/transaction/verify/' . $data['reference']);

                Log::info('Payaza Verification Response:', $response->json() ?? []);

                if (!$response->successful()) {
                    Log::error('Payaza Verification Failed:', [
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                    return response('Transaction verification failed', 400);
                }

                $verificationData = $response->json();

                // Update deposit details
                $deposit->detail = [
                    'ipn_response' => $data,
                    'verification_response' => $verificationData
                ];
                $deposit->save();

                // Process successful payment
                if ($data['status'] === 'success' && $deposit->status == Status::PAYMENT_INITIATE) {
                    // Convert amount from kobo to naira for comparison
                    $receivedAmount = $data['amount'] / 100;
                    if ($receivedAmount >= round($deposit->final_amo, 2)) {
                        PaymentController::userDataUpdate($deposit);
                        $notify = "Transaction Successful";
                        return redirect('/user/deposit/new')->with('message', $notify);
                    }
                    Log::error('Payaza IPN: Amount mismatch', [
                        'expected' => $deposit->final_amo,
                        'received' => $receivedAmount
                    ]);
                }

                return response('Payment status: ' . $data['status']);

            } catch (\Exception $e) {
                Log::error('Payaza Verification Error:', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return response('Error verifying transaction: ' . $e->getMessage(), 500);
            }

        } catch (\Exception $e) {
            Log::error('Payaza IPN Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response('Error processing IPN: ' . $e->getMessage(), 500);
        }
    }
}
