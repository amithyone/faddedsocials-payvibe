<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Deposit;

echo "=== Recent CheckoutNow Transactions ===\n\n";

$deposits = Deposit::where('method_code', 121)
    ->orderBy('id', 'desc')
    ->limit(30)
    ->get();

if ($deposits->isEmpty()) {
    echo "No CheckoutNow transactions found.\n";
    exit(0);
}

echo "Found {$deposits->count()} CheckoutNow transaction(s):\n\n";

foreach ($deposits as $d) {
    $detail = $d->detail ?? [];
    $trxId = $detail['transaction_id'] ?? 'N/A';
    $statusText = $d->status == 1 ? 'Success' : ($d->status == 0 ? 'Pending' : 'Failed');
    
    echo "ID: {$d->id}\n";
    echo "  TRX: {$d->trx}\n";
    echo "  Detail TRX: {$trxId}\n";
    echo "  Status: {$d->status} ({$statusText})\n";
    echo "  Amount: {$d->amount}\n";
    echo "  Updated: {$d->updated_at}\n";
    
    // Check if this matches the search
    if (isset($argv[1])) {
        $search = $argv[1];
        if (strpos($trxId, $search) !== false || strpos($d->trx, $search) !== false || $d->id == $search) {
            echo "  ^^^ MATCHES SEARCH: {$search} ^^^\n";
        }
    }
    
    echo "\n";
}
