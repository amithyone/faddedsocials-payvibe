<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Deposit;
use App\Models\User;
use App\Services\WebhookService;

$search = isset($argv[1]) ? $argv[1] : null;

if (!$search) {
    echo "Usage: php find_and_resend.php <transaction_ref_or_id>\n";
    exit(1);
}

echo "=== Searching for Transaction ===\n\n";

// Try by TRX first
$deposit = Deposit::where('trx', $search)->first();

// If not found, try by ID
if (!$deposit && is_numeric($search)) {
    $deposit = Deposit::where('id', $search)->first();
}

// If still not found, search in detail field
if (!$deposit) {
    $deposits = Deposit::where('method_code', 121)
        ->where('status', 1)
        ->orderBy('updated_at', 'desc')
        ->limit(10)
        ->get();
    
    echo "Transaction '{$search}' not found directly.\n";
    echo "Recent successful CheckoutNow transactions:\n\n";
    
    foreach ($deposits as $d) {
        $detail = $d->detail ?? [];
        $trxId = $detail['transaction_id'] ?? $d->trx;
        echo "  ID: {$d->id}, TRX: {$d->trx}, Detail TRX: {$trxId}, Updated: {$d->updated_at}\n";
        
        if (strpos($trxId, $search) !== false || strpos($d->trx, $search) !== false) {
            $deposit = $d;
            echo "  ^ MATCH FOUND!\n";
            break;
        }
    }
    
    if (!$deposit) {
        echo "\n✗ Transaction not found. Please check the reference.\n";
        exit(1);
    }
}

echo "\n=== Found Transaction ===\n";
echo "  ID: {$deposit->id}\n";
echo "  TRX: {$deposit->trx}\n";
echo "  Status: {$deposit->status} " . ($deposit->status == 1 ? '(Success)' : ($deposit->status == 0 ? '(Pending)' : '(Failed)')) . "\n";
echo "  Method Code: {$deposit->method_code}\n";
echo "  Gateway: " . ($deposit->gateway ? $deposit->gateway->name : "Unknown") . "\n\n";

if ($deposit->status != 1) {
    echo "⚠ WARNING: Transaction status is not 'Success' (status = {$deposit->status})\n";
    echo "This may not update correctly in Xtrabusiness.\n\n";
}

$user = User::find($deposit->user_id);
if (!$user) {
    echo "✗ User not found\n";
    exit(1);
}

$deposit->load('gateway');

echo "=== Resending Webhooks ===\n\n";

try {
    echo "1. Sending successful transaction webhook...\n";
    $result1 = WebhookService::sendSuccessfulTransaction($deposit, $user);
    echo $result1 ? "   ✓ Sent successfully\n" : "   ✗ Failed\n";
    
    echo "\n2. Sending credited amount webhook...\n";
    $result2 = WebhookService::sendCreditedAmountToXtrabusiness($deposit, $user);
    echo $result2 ? "   ✓ Sent successfully\n" : "   ✗ Failed\n";
    
    if ($result1 && $result2) {
        echo "\n=== SUCCESS ===\n";
        echo "Both webhooks sent! Check Xtrabusiness dashboard.\n";
    } else {
        echo "\n=== WARNING ===\n";
        echo "Some webhooks may have failed. Check logs.\n";
    }
    
} catch (\Exception $e) {
    echo "✗ Error: {$e->getMessage()}\n";
}
