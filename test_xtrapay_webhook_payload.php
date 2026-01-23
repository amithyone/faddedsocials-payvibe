<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Deposit;
use App\Models\User;

echo "=== Testing Xtrapay Successful Transaction Webhook Payload ===\n\n";

// Find a recent successful Xtrapay transaction (method_code 118)
$deposit = Deposit::where('method_code', 118)
    ->where('status', 1) // Successful
    ->orderBy('id', 'desc')
    ->first();

if (!$deposit) {
    echo "❌ No successful Xtrapay transactions found.\n";
    exit(1);
}

echo "Found Xtrapay transaction:\n";
echo "  Deposit ID: {$deposit->id}\n";
echo "  TRX: {$deposit->trx}\n";
echo "  Amount: ₦" . number_format($deposit->amount, 2) . "\n";
echo "  Status: " . ($deposit->status == 1 ? 'Success' : 'Other') . "\n";
echo "  Created: {$deposit->created_at}\n\n";

// Load gateway relationship
$deposit->load('gateway');
$user = User::find($deposit->user_id);

if (!$user) {
    echo "❌ User not found for deposit {$deposit->id}\n";
    exit(1);
}

echo "User: {$user->email}\n";
echo "Gateway Code: {$deposit->gateway->code}\n";
echo "Gateway Name: {$deposit->gateway->name}\n\n";

// Simulate what WebhookService::sendToXtrabusiness would send
$apiCode = env('XTRABUSINESS_API_CODE', 'faddedsocials');
$creditedAmount = max(0, $deposit->amount);
$creditedAmount = round($creditedAmount / 10) * 10;
$totalPaid = $deposit->final_amo ?? $deposit->amount + $deposit->charge;
$charges = $deposit->charge ?? 0;

$paymentMethod = 'xtrapay';
$description = 'Deposit via Xtrapay';

$normalizedStatus = 'success';

// Metadata for successful transaction
$metadata = [
    'event' => 'xtrapay_notification',
    'notification_received_at' => now()->format('Y-m-d H:i:s')
];

if (isset($deposit->detail['reference'])) {
    $metadata['xtrapay_reference'] = $deposit->detail['reference'];
}

$metadata['deposit_id'] = $deposit->id;
$metadata['user_id'] = $user->id;
$metadata['total_paid'] = $totalPaid;
$metadata['charges'] = $charges;

$payload = [
    'site_api_code' => $apiCode,
    'reference' => $deposit->trx,
    'amount' => $creditedAmount,
    'currency' => 'NGN',
    'status' => $normalizedStatus,
    'payment_method' => $paymentMethod,
    'customer_email' => $user->email,
    'customer_name' => trim($user->firstname . ' ' . $user->lastname),
    'description' => $description,
    'external_id' => (string) $deposit->id,
    'metadata' => $metadata,
    'timestamp' => $deposit->created_at ? $deposit->created_at->toISOString() : now()->toISOString()
];

echo "=== Webhook Payload (as sent to XtrapayBusiness) ===\n";
echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";

echo "=== Expected Format Match Check ===\n";
echo "✓ site_api_code: " . ($payload['site_api_code'] ? 'Present' : 'Missing') . "\n";
echo "✓ reference: " . ($payload['reference'] ? 'Present' : 'Missing') . "\n";
echo "✓ amount: " . ($payload['amount'] ? 'Present' : 'Missing') . "\n";
echo "✓ currency: " . ($payload['currency'] === 'NGN' ? 'Correct (NGN)' : 'Incorrect') . "\n";
echo "✓ status: " . ($payload['status'] === 'success' ? 'Correct (success)' : 'Incorrect') . "\n";
echo "✓ payment_method: " . ($payload['payment_method'] === 'xtrapay' ? 'Correct (xtrapay)' : 'Incorrect') . "\n";
echo "✓ customer_email: " . ($payload['customer_email'] ? 'Present' : 'Missing') . "\n";
echo "✓ customer_name: " . ($payload['customer_name'] ? 'Present' : 'Missing') . "\n";
echo "✓ description: " . ($payload['description'] === 'Deposit via Xtrapay' ? 'Correct' : 'Incorrect') . "\n";
echo "✓ external_id: " . (isset($payload['external_id']) ? 'Present' : 'Missing') . "\n";
echo "✓ metadata.event: " . ($payload['metadata']['event'] === 'xtrapay_notification' ? 'Correct' : 'Incorrect') . "\n";
echo "✓ metadata.notification_received_at: " . (isset($payload['metadata']['notification_received_at']) ? 'Present' : 'Missing') . "\n";
echo "✓ timestamp: " . (isset($payload['timestamp']) ? 'Present' : 'Missing') . "\n\n";

echo "=== DONE ===\n";
