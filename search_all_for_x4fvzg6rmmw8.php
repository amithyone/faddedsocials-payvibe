<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Deposit;
use App\Models\User;
use App\Services\WebhookService;

$search = 'X4FVZG6RMMW8';

echo "=== Searching ALL deposits for: {$search} ===\n\n";

// Search all deposits
$allDeposits = Deposit::where('trx', 'like', "%{$search}%")
    ->orWhere('id', $search)
    ->get();

// Also search CheckoutNow deposits in detail field
$checkoutNowDeposits = Deposit::where('method_code', 121)
    ->where('status', 1) // Successful
    ->orderBy('updated_at', 'desc')
    ->get();

echo "Searching in TRX field: " . $allDeposits->count() . " found\n";

$found = null;

foreach ($checkoutNowDeposits as $deposit) {
    $detail = $deposit->detail ?? [];
    $detailJson = json_encode($detail);
    
    if (strpos($detailJson, $search) !== false || 
        strpos($deposit->trx, $search) !== false ||
        (isset($detail['transaction_id']) && strpos($detail['transaction_id'], $search) !== false)) {
        
        $found = $deposit;
        echo "\n✓ FOUND MATCH!\n";
        echo "  Deposit ID: {$deposit->id}\n";
        echo "  TRX: {$deposit->trx}\n";
        echo "  Status: {$deposit->status} " . ($deposit->status == 1 ? '(Success)' : '') . "\n";
        echo "  Detail: " . json_encode($detail, JSON_PRETTY_PRINT) . "\n";
        break;
    }
}

if (!$found) {
    echo "\n✗ Transaction '{$search}' not found in any CheckoutNow deposits.\n";
    echo "\nRecent successful CheckoutNow transactions that might need webhooks:\n\n";
    
    foreach ($checkoutNowDeposits->take(10) as $d) {
        $detail = $d->detail ?? [];
        $trxId = $detail['transaction_id'] ?? $d->trx;
        echo "  ID: {$d->id}, TRX: {$d->trx}, Detail TRX: {$trxId}, Updated: {$d->updated_at}\n";
    }
    
    exit(1);
}

// Found it! Resend webhooks
echo "\n=== Resending Webhooks ===\n\n";

$user = User::find($found->user_id);
if (!$user) {
    echo "✗ User not found\n";
    exit(1);
}

$found->load('gateway');

try {
    echo "1. Sending successful transaction webhook...\n";
    $result1 = WebhookService::sendSuccessfulTransaction($found, $user);
    echo $result1 ? "   ✓ Sent successfully\n" : "   ✗ Failed\n";
    
    echo "\n2. Sending credited amount webhook...\n";
    $result2 = WebhookService::sendCreditedAmountToXtrabusiness($found, $user);
    echo $result2 ? "   ✓ Sent successfully\n" : "   ✗ Failed\n";
    
    if ($result1 && $result2) {
        echo "\n=== SUCCESS ===\n";
        echo "Webhooks sent! Check Xtrabusiness dashboard.\n";
    }
} catch (\Exception $e) {
    echo "✗ Error: {$e->getMessage()}\n";
}
