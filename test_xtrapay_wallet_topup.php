<?php
/**
 * XtraPay Wallet Top-Up Test
 * Tests if we can get account number for wallet top-up
 */

echo "=== XtraPay Wallet Top-Up Test ===\n\n";

// Load Laravel environment
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Http;

echo "1. Checking XtraPay configuration...\n";

$accessKey = env('XTRAPAY_ACCESS_KEY');

if (empty($accessKey)) {
    echo "❌ XTRAPAY_ACCESS_KEY not found in .env\n";
    echo "   Please add XTRAPAY_ACCESS_KEY to your .env file\n";
    exit(1);
}

echo "✅ Access key found: " . substr($accessKey, 0, 10) . "...\n";

// Test amount for wallet top-up
$amount = 5000.00; // Test with NGN 5,000
// Calculate charge: 1.5% + 100 for all amounts
$charge = 100 + round($amount * 0.015, 2);
$finalAmount = (int) round($amount + $charge, 0);

echo "   Test Amount: NGN " . number_format($amount, 2) . "\n";
echo "   Charge: NGN " . number_format($charge, 2) . "\n";
echo "   Final Amount: NGN " . number_format($finalAmount, 2) . "\n";

// Generate unique reference
do {
    $reference = substr(str_shuffle(time() . mt_rand(100000, 999999)), 0, 12);
    $exists = \App\Models\Deposit::where('trx', $reference)->exists();
} while ($exists);

echo "\n2. Generating virtual account...\n";
echo "   Reference: $reference\n";
echo "   API Endpoint: https://mobile.xtrapay.ng/api/faddedsocials/generateAccount\n";

// Test data for wallet top-up (using fadded_sms service as per wallet top-up guide)
$testData = [
    'reference' => $reference,
    'amount' => $finalAmount,
    'service' => 'fadded_sms'
];

try {
    $response = Http::withToken($accessKey)
        ->timeout(30)
        ->post('https://mobile.xtrapay.ng/api/faddedsocials/generateAccount', $testData);
    
    echo "\n3. API Response:\n";
    echo "   HTTP Status: " . $response->status() . "\n";
    
    if ($response->successful()) {
        $data = $response->json();
        
        echo "\n✅ API call successful!\n";
        echo "   Response: " . json_encode($data, JSON_PRETTY_PRINT) . "\n\n";
        
        if (isset($data['statusCode']) && $data['statusCode'] == 200) {
            echo "✅ Status code: 200 (Success)\n";
            
            if (isset($data['data']) && !empty($data['data'])) {
                $accountData = $data['data'];
                
                echo "\n4. Virtual Account Details:\n";
                echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
                
                if (!empty($accountData['accountNumber'])) {
                    echo "✅ ACCOUNT NUMBER: " . $accountData['accountNumber'] . "\n";
                } else {
                    echo "❌ ACCOUNT NUMBER: NOT FOUND\n";
                }
                
                echo "   Bank Name: " . ($accountData['bank'] ?? 'N/A') . "\n";
                echo "   Account Name: " . ($accountData['accountName'] ?? 'N/A') . "\n";
                echo "   Reference: " . ($accountData['reference'] ?? $reference) . "\n";
                echo "   Amount: NGN " . number_format($accountData['amount'] ?? $finalAmount, 2) . "\n";
                echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
                
                if (!empty($accountData['accountNumber'])) {
                    echo "\n🎉 SUCCESS: Account number retrieved successfully!\n";
                    echo "   ✅ Virtual Account Number: " . $accountData['accountNumber'] . "\n";
                    echo "   ✅ You can use this account number for wallet top-up\n";
                } else {
                    echo "\n⚠️  WARNING: Account number is empty in response\n";
                    echo "   Check the API response structure above\n";
                }
            } else {
                echo "\n❌ ERROR: No 'data' field in response or data is empty\n";
                echo "   Full response: " . json_encode($data, JSON_PRETTY_PRINT) . "\n";
            }
        } else {
            echo "\n❌ ERROR: API returned error status\n";
            echo "   Status Code: " . ($data['statusCode'] ?? 'N/A') . "\n";
            echo "   Message: " . ($data['message'] ?? 'N/A') . "\n";
        }
    } else {
        echo "\n❌ ERROR: API call failed\n";
        echo "   HTTP Status: " . $response->status() . "\n";
        echo "   Response Body: " . $response->body() . "\n";
        
        // Check if it's an HTML response (Laravel error page)
        if (strpos($response->body(), '<!DOCTYPE html>') !== false) {
            echo "\n⚠️  WARNING: Received HTML response instead of JSON\n";
            echo "   This suggests the API endpoint might be incorrect or there's an authentication issue\n";
        }
    }
    
} catch (\Exception $e) {
    echo "\n❌ EXCEPTION: " . $e->getMessage() . "\n";
    echo "   File: " . $e->getFile() . " (Line: " . $e->getLine() . ")\n";
}

echo "\n=== Test Complete ===\n";
?>



