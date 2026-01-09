<?php

// Test script to check PayVibe configuration
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Gateway;
use App\Models\GatewayCurrency;
use App\Models\Deposit;
use App\Models\User;

echo "=== PayVibe Configuration Test ===\n\n";

// Check if PayVibe gateway exists
$gateway = Gateway::where('alias', 'PayVibe')->first();
if ($gateway) {
    echo "✅ PayVibe Gateway Found:\n";
    echo "   Code: " . $gateway->code . "\n";
    echo "   Name: " . $gateway->name . "\n";
    echo "   Status: " . $gateway->status . "\n";
    echo "   Parameters: " . $gateway->gateway_parameters . "\n\n";
} else {
    echo "❌ PayVibe Gateway NOT Found\n\n";
}

// Check if PayVibe currency exists
$currency = GatewayCurrency::where('gateway_alias', 'PayVibe')->first();
if ($currency) {
    echo "✅ PayVibe Currency Found:\n";
    echo "   Method Code: " . $currency->method_code . "\n";
    echo "   Name: " . $currency->name . "\n";
    echo "   Currency: " . $currency->currency . "\n";
    echo "   Parameters: " . $currency->gateway_parameter . "\n\n";
} else {
    echo "❌ PayVibe Currency NOT Found\n\n";
}

// Test creating a deposit
echo "=== Testing Deposit Creation ===\n";
try {
    $user = User::first();
    if ($user) {
        $deposit = new Deposit();
        $deposit->user_id = $user->id;
        $deposit->method_code = 120; // PayVibe method code
        $deposit->method_currency = 'NGN';
        $deposit->amount = 1000;
        $deposit->charge = 100;
        $deposit->rate = 1;
        $deposit->final_amo = 1100;
        $deposit->trx = 'TEST' . time();
        $deposit->save();
        
        echo "✅ Test deposit created successfully\n";
        echo "   Deposit ID: " . $deposit->id . "\n";
        echo "   TRX: " . $deposit->trx . "\n";
        
        // Test gateway currency relationship
        $gatewayCurrency = $deposit->gatewayCurrency();
        if ($gatewayCurrency) {
            echo "✅ Gateway currency relationship works\n";
            echo "   Gateway: " . $gatewayCurrency->name . "\n";
        } else {
            echo "❌ Gateway currency relationship failed\n";
        }
        
        // Clean up test deposit
        $deposit->delete();
        echo "✅ Test deposit cleaned up\n";
        
    } else {
        echo "❌ No users found in database\n";
    }
} catch (\Exception $e) {
    echo "❌ Error creating test deposit: " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n"; 