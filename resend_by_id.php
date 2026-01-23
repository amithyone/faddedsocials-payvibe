<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Deposit;
use App\Models\User;
use App\Services\WebhookService;

echo "=== Resending Webhook by Deposit ID ===\n\n";

$depositId = isset($argv[1]) ? (int)$argv[1] : null;

if (!$depositId) {
    echo "Usage: php resend_by_id.php <deposit_id>\n";
    echo "Example: php resend_by_id.php 197993\n";
    exit(1);
}

echo "Looking for deposit ID: {$depositId}\n\n";

$deposit = Deposit::where('id', $depositId)
    ->with('gateway')
    ->first();

if (!$deposit) {
    echo "✗ Deposit not found: {$depositId}\n";
    exit(1);
}

echo "Found deposit:\n";
echo "  ID: {$deposit->id}\n";
echo "  TRX: {$deposit->trx}\n";
echo "  Status: {$deposit->status} " . ($deposit->status == 1 ? '(Success)' : ($deposit->status == 0 ? '(Pending)' : '(Failed)')) . "\n";
echo "  Method Code: {$deposit->method_code}\n";
echo "  Amount: {$deposit->amount}\n";
echo "  Charge: {$deposit->charge}\n";
echo "  Final Amount: {$deposit->final_amo}\n";
echo "  Updated: {$deposit->updated_at}\n";
echo "  Gateway: " . ($deposit->gateway ? $deposit->gateway->name . " (Code: {$deposit->gateway->code})" : "Not loaded") . "\n\n";

$user = User::find($deposit->user_id);

if (!$user) {
    echo "✗ User not found\n";
    exit(1);
}

echo "User: {$user->email} ({$user->firstname} {$user->lastname})\n\n";

$deposit->refresh();
$deposit->load('gateway');

if (!$deposit->gateway) {
    echo "✗ Gateway not loaded!\n";
    exit(1);
}

echo "=== Sending Webhooks ===\n\n";

try {
    echo "1. Sending successful transaction webhook...\n";
    $result1 = WebhookService::sendSuccessfulTransaction($deposit, $user);
    echo $result1 ? "   ✓ Sent\n" : "   ✗ Failed\n";
    
    echo "\n2. Sending credited amount webhook...\n";
    $result2 = WebhookService::sendCreditedAmountToXtrabusiness($deposit, $user);
    echo $result2 ? "   ✓ Sent\n" : "   ✗ Failed\n";
    
    if ($result1 && $result2) {
        echo "\n=== SUCCESS ===\n";
        echo "Webhooks sent! Check Xtrabusiness.\n";
    }
    
} catch (\Exception $e) {
    echo "✗ Error: {$e->getMessage()}\n";
}
