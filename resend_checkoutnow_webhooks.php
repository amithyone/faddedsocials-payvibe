<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Deposit;
use App\Models\User;
use App\Services\WebhookService;
use Carbon\Carbon;

echo "=== Resending CheckoutNow Webhooks ===\n\n";

// Look back 4 hours
$hours = isset($argv[1]) ? (int)$argv[1] : 4;
$since = Carbon::now()->subHours($hours);

echo "Looking for successful CheckoutNow transactions since {$since->toDateTimeString()}...\n\n";

// Find all successful CheckoutNow deposits
$deposits = Deposit::where('method_code', 121)
    ->where('status', 1) // Successful
    ->where('updated_at', '>=', $since)
    ->with('gateway')
    ->orderBy('updated_at', 'desc')
    ->get();

if ($deposits->isEmpty()) {
    echo "No successful CheckoutNow transactions found in the last {$hours} hours.\n";
    echo "Checking all successful CheckoutNow transactions...\n\n";
    
    // Check all successful CheckoutNow transactions
    $allDeposits = Deposit::where('method_code', 121)
        ->where('status', 1)
        ->with('gateway')
        ->orderBy('updated_at', 'desc')
        ->limit(10)
        ->get(['id', 'trx', 'status', 'updated_at']);
    
    if ($allDeposits->isEmpty()) {
        echo "No successful CheckoutNow transactions found at all.\n";
        exit(0);
    }
    
    echo "Found " . $allDeposits->count() . " recent successful CheckoutNow transaction(s):\n";
    foreach ($allDeposits as $d) {
        echo "  - ID: {$d->id}, TRX: {$d->trx}, Updated: {$d->updated_at}\n";
    }
    echo "\nTo resend webhooks for older transactions, run:\n";
    echo "  php resend_checkoutnow_webhooks.php 24  (for last 24 hours)\n";
    echo "  php resend_checkoutnow_webhooks.php 48  (for last 48 hours)\n";
    exit(0);
}

echo "Found {$deposits->count()} successful CheckoutNow transaction(s).\n\n";

$successCount = 0;
$failCount = 0;

foreach ($deposits as $deposit) {
    $user = User::find($deposit->user_id);
    
    if (!$user) {
        echo "✗ User not found for deposit ID: {$deposit->id}\n";
        $failCount++;
        continue;
    }
    
    echo "Processing deposit ID: {$deposit->id}, Transaction: {$deposit->trx}\n";
    
    try {
        // Refresh deposit to ensure gateway relationship is loaded
        $deposit->refresh();
        $deposit->load('gateway');
        
        // Send successful transaction webhook
        $result1 = WebhookService::sendSuccessfulTransaction($deposit, $user);
        
        // Send credited amount webhook
        $result2 = WebhookService::sendCreditedAmountToXtrabusiness($deposit, $user);
        
        if ($result1 && $result2) {
            echo "  ✓ Webhooks sent successfully\n";
            $successCount++;
        } else {
            echo "  ⚠ Some webhooks may have failed\n";
            $failCount++;
        }
        
    } catch (\Exception $e) {
        echo "  ✗ Error: {$e->getMessage()}\n";
        $failCount++;
    }
    
    echo "\n";
}

echo "=== Summary ===\n";
echo "Total processed: {$deposits->count()}\n";
echo "Successful: {$successCount}\n";
echo "Failed: {$failCount}\n";
