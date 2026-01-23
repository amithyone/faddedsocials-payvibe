<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Deposit;
use App\Models\User;
use App\Services\WebhookService;
use Illuminate\Support\Facades\Log;

echo "=== Resending Webhook for Specific Transaction ===\n\n";

// Get transaction reference from command line
$trx = isset($argv[1]) ? $argv[1] : null;

if (!$trx) {
    echo "Usage: php resend_specific_webhook.php <transaction_reference>\n";
    echo "Example: php resend_specific_webhook.php X4FVZG6RMMW8\n";
    exit(1);
}

echo "Looking for transaction: {$trx}\n\n";

// Find the deposit
$deposit = Deposit::where('trx', $trx)
    ->with('gateway')
    ->first();

if (!$deposit) {
    echo "✗ Transaction not found: {$trx}\n";
    exit(1);
}

echo "Found transaction:\n";
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
    echo "✗ User not found for deposit ID: {$deposit->id}\n";
    exit(1);
}

echo "User: {$user->email} ({$user->firstname} {$user->lastname})\n\n";

// Refresh deposit to ensure gateway relationship is loaded
$deposit->refresh();
$deposit->load('gateway');

if (!$deposit->gateway) {
    echo "✗ Gateway relationship not found!\n";
    exit(1);
}

echo "Gateway loaded: {$deposit->gateway->name} (Code: {$deposit->gateway->code})\n\n";

echo "=== Sending Webhooks ===\n\n";

try {
    // Send successful transaction webhook
    echo "1. Sending successful transaction webhook...\n";
    $result1 = WebhookService::sendSuccessfulTransaction($deposit, $user);
    
    if ($result1) {
        echo "   ✓ Successful transaction webhook sent\n";
    } else {
        echo "   ✗ Failed to send successful transaction webhook\n";
    }
    
    echo "\n";
    
    // Send credited amount webhook
    echo "2. Sending credited amount webhook...\n";
    $result2 = WebhookService::sendCreditedAmountToXtrabusiness($deposit, $user);
    
    if ($result2) {
        echo "   ✓ Credited amount webhook sent\n";
    } else {
        echo "   ✗ Failed to send credited amount webhook\n";
    }
    
    echo "\n";
    
    if ($result1 && $result2) {
        echo "=== SUCCESS ===\n";
        echo "Both webhooks sent successfully!\n";
        echo "\nCheck Xtrabusiness dashboard to verify the transaction was updated.\n";
    } else {
        echo "=== WARNING ===\n";
        echo "Some webhooks may have failed. Check logs for details.\n";
    }
    
} catch (\Exception $e) {
    echo "✗ Error: {$e->getMessage()}\n";
    echo "Trace: {$e->getTraceAsString()}\n";
    exit(1);
}

echo "\n=== Webhook Payload Details ===\n";
echo "The webhooks were sent with:\n";
echo "  - action: 'updated' (to update existing transaction)\n";
echo "  - status: 'success'\n";
echo "  - payment_method: 'checkoutnow'\n";
echo "  - reference: {$deposit->trx}\n";
echo "  - amount: {$deposit->amount}\n";
echo "  - total_paid: " . ($deposit->final_amo ?? $deposit->amount + $deposit->charge) . "\n";
echo "  - charges: {$deposit->charge}\n";
