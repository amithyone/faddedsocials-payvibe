<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Deposit;
use App\Models\Gateway;
use App\Models\User;
use App\Services\WebhookService;
use Carbon\Carbon;

echo "=== TEST CheckoutNow Webhook Format ===\n\n";

$user = User::first();
$gateway = Gateway::where('code', 121)->first();   // CheckoutNow

if (!$user || !$gateway) {
    echo "✗ User or gateway not found\n";
    exit(1);
}

// Create fake deposit (NOT saved to DB)
$deposit = new Deposit();
$deposit->id = 999999;
$deposit->user_id = $user->id;
$deposit->method_code = 121;
$deposit->amount = 2000.00;
$deposit->charge = 70.00;
$deposit->final_amo = 2070.00;
$deposit->trx = 'TEST-CHECKOUTNOW-' . time();
$deposit->status = 1;
$deposit->created_at = Carbon::now()->subMinutes(5);
$deposit->updated_at = Carbon::now();
$deposit->detail = ['transaction_id' => 'X4FVZG6RMMW8'];
$deposit->setRelation('gateway', $gateway);

$ref = $deposit->trx;
echo "Test Reference: {$ref}\n\n";

echo "=== Sending Webhooks ===\n\n";

echo "1. Pending (created) webhook...\n";
$result1 = WebhookService::sendToXtrabusiness($deposit, $user, 'pending');
echo $result1 ? "   ✓ Sent\n" : "   ✗ Failed\n";

echo "\n2. Success (updated) webhook...\n";
$result2 = WebhookService::sendToXtrabusiness($deposit, $user, 'successful');
echo $result2 ? "   ✓ Sent\n" : "   ✗ Failed\n";

echo "\n3. Credited amount webhook...\n";
$result3 = WebhookService::sendCreditedAmountToXtrabusiness($deposit, $user);
echo $result3 ? "   ✓ Sent\n" : "   ✗ Failed\n";

echo "\n=== DONE ===\n";
echo "Check XtrapayBusiness for reference: {$ref}\n";
echo "Expected: payment_method=checkoutnow, status=success\n";
