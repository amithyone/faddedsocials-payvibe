<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Deposit;
use App\Models\User;
use App\Services\WebhookService;

echo "=== Checking Deposit 197993 ===\n\n";

$deposit = Deposit::find(197993);

if (!$deposit) {
    echo "✗ Deposit 197993 not found\n";
    exit(1);
}

echo "Deposit Details:\n";
echo "  ID: {$deposit->id}\n";
echo "  TRX: {$deposit->trx}\n";
echo "  Method Code: {$deposit->method_code}\n";
echo "  Status: {$deposit->status} " . ($deposit->status == 1 ? '(Success)' : ($deposit->status == 0 ? '(Pending)' : '(Failed)')) . "\n";
echo "  Amount: {$deposit->amount}\n";
echo "  Charge: {$deposit->charge}\n";
echo "  Final Amount: {$deposit->final_amo}\n";
echo "  Updated: {$deposit->updated_at}\n";

$deposit->load('gateway');
if ($deposit->gateway) {
    echo "  Gateway: {$deposit->gateway->name} (Code: {$deposit->gateway->code})\n";
}

echo "\nDetail Field:\n";
$detail = $deposit->detail ?? [];
echo json_encode($detail, JSON_PRETTY_PRINT) . "\n\n";

// Check if X4FVZG6RMMW8 is in detail
$found = false;
if (isset($detail['transaction_id']) && strpos($detail['transaction_id'], 'X4FVZG6RMMW8') !== false) {
    echo "✓ Found X4FVZG6RMMW8 in detail.transaction_id\n";
    $found = true;
}

if (strpos($deposit->trx, 'X4FVZG6RMMW8') !== false) {
    echo "✓ Found X4FVZG6RMMW8 in TRX\n";
    $found = true;
}

if (!$found) {
    echo "⚠ X4FVZG6RMMW8 not found in this deposit\n";
}

// If this is a CheckoutNow transaction (method_code 121) and successful, resend webhook
if ($deposit->method_code == 121 && $deposit->status == 1) {
    echo "\n=== This is a successful CheckoutNow transaction ===\n";
    echo "Resending webhooks...\n\n";
    
    $user = User::find($deposit->user_id);
    if (!$user) {
        echo "✗ User not found\n";
        exit(1);
    }
    
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
} elseif ($deposit->method_code == 121 && $deposit->status == 0) {
    echo "\n⚠ This is a CheckoutNow transaction but status is PENDING, not SUCCESS\n";
    echo "Cannot send success webhook for pending transaction.\n";
} elseif ($deposit->method_code != 121) {
    echo "\n⚠ This is NOT a CheckoutNow transaction (method_code = {$deposit->method_code})\n";
    echo "This is a " . ($deposit->gateway ? $deposit->gateway->name : "Unknown") . " transaction.\n";
}
