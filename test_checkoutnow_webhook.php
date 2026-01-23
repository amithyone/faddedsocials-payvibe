<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\\Contracts\\Console\\Kernel')->bootstrap();

use App\\Models\\Deposit;
use App\\Models\\Gateway;
use App\\Models\\User;
use App\\Services\\WebhookService;
use Carbon\\Carbon;

echo "=== TEST CheckoutNow → Xtrabusiness Webhook ===\n\n";

// Pick any existing user as test subject (no balance changes in this script)
$user = User::first();

if (!$user) {
    echo "✗ No users found in database.\n";
    exit(1);
}

echo "Using user: {$user->id} ({$user->email})\n";

// Get CheckoutNow gateway
$gateway = Gateway::where('code', 121)->first();

if (!$gateway) {
    echo "✗ CheckoutNow gateway (code 121) not found in gateways table.\n";
    exit(1);
}

echo "Using gateway: {$gateway->name} (Code: {$gateway->code})\n\n";

// Build a fake Deposit model in memory (NOT saved to DB)
$deposit = new Deposit();
$deposit->id = 999999; // fake ID
$deposit->user_id = $user->id;
$deposit->method_code = 121;
$deposit->amount = 1234.00;            // test credited amount
$deposit->charge = 66.00;              // test charge
$deposit->final_amo = 1300.00;         // amount + charge
$deposit->trx = 'TEST-CHECKOUTNOW-' . time();
$deposit->status = 1;                  // treat as successful
$deposit->created_at = Carbon::now()->subMinutes(5);
$deposit->updated_at = Carbon::now();

// Attach gateway relation manually
$deposit->setRelation('gateway', $gateway);

echo "Test reference (trx): {$deposit->trx}\n";
echo "Amount: {$deposit->amount}, Charge: {$deposit->charge}, Final: {$deposit->final_amo}\n\n";

// 1) Send pending (created) webhook
echo "1. Sending PENDING (created) webhook...\n";
$resultPending = WebhookService::sendToXtrabusiness($deposit, $user, 'pending');
echo $resultPending ? "   ✓ Pending webhook sent\n" : "   ✗ Pending webhook failed\n";

// 2) Send success (updated) webhook
echo "\n2. Sending SUCCESS (updated) webhook...\n";
$resultSuccess = WebhookService::sendToXtrabusiness($deposit, $user, 'successful');
echo $resultSuccess ? "   ✓ Success webhook sent\n" : "   ✗ Success webhook failed\n";

echo "\n=== DONE ===\n";
echo "Look in Xtrabusiness for reference: {$deposit->trx} with payment_method=checkoutnow.\n";
