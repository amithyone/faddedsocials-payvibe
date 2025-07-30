<?php

// Debug script to check PayVibe configuration
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Gateway;
use App\Models\GatewayCurrency;

echo "=== PayVibe Configuration Debug ===\n\n";

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
    echo "   Min Amount: " . $currency->min_amount . "\n";
    echo "   Max Amount: " . $currency->max_amount . "\n";
    echo "   Parameters: " . $currency->gateway_parameter . "\n\n";
} else {
    echo "❌ PayVibe Currency NOT Found\n\n";
}

// Check environment variables
echo "=== Environment Variables ===\n";
echo "PAYVIBE_PUBLIC_KEY: " . (env('PAYVIBE_PUBLIC_KEY') ? '✅ Set' : '❌ Not Set') . "\n";
echo "PAYVIBE_SECRET_KEY: " . (env('PAYVIBE_SECRET_KEY') ? '✅ Set' : '❌ Not Set') . "\n";
echo "PAYVIBE_PRODUCT_IDENTIFIER: " . (env('PAYVIBE_PRODUCT_IDENTIFIER') ? '✅ Set' : '❌ Not Set') . "\n\n";

// Check if PayVibe is available in gateway_currency
$allGateways = GatewayCurrency::whereHas('method', function($query) {
    $query->where('status', 1);
})->get();

echo "=== Available Gateways ===\n";
foreach ($allGateways as $gateway) {
    echo "   " . $gateway->name . " (Code: " . $gateway->method_code . ")\n";
}

echo "\n=== Debug Complete ===\n"; 