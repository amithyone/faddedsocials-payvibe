<?php
/**
 * PayVibe Account Generation Test
 * Tests if virtual account generation is working with the corrected API endpoints
 */

echo "=== PayVibe Account Generation Test ===\n\n";

// Load Laravel environment
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "1. Testing PayVibe API endpoint configuration...\n";

// Test the API endpoint directly
$baseUrl = config('services.payvibe.base_url', 'https://payvibeapi.six3tech.com/api');
$apiUrl = $baseUrl . '/v1/payments/virtual-accounts/initiate';
$secretKey = env('PAYVIBE_SECRET_KEY');

if (empty($secretKey)) {
    echo "❌ PAYVIBE_SECRET_KEY not found in .env\n";
    exit(1);
}

echo "✅ Secret key found: " . substr($secretKey, 0, 10) . "...\n";
echo "✅ API URL: $apiUrl\n";

// Test data
$testData = [
    'reference' => 'TEST_' . time(),
    'amount' => 1000.00,
    'product_identifier' => env('PAYVIBE_PRODUCT_IDENTIFIER', 'socials')
];

echo "\n2. Testing API call with test data...\n";
echo "   Reference: {$testData['reference']}\n";
echo "   Amount: {$testData['amount']}\n";
echo "   Product Identifier: {$testData['product_identifier']}\n";

try {
    $response = Http::withToken($secretKey)
        ->timeout(30)
        ->post($apiUrl, $testData);
    
    echo "\n3. API Response:\n";
    echo "   Status Code: " . $response->status() . "\n";
    echo "   Response Headers: " . json_encode($response->headers()) . "\n";
    echo "   Response Body (first 500 chars): " . substr($response->body(), 0, 500) . "\n";
    
    if ($response->successful()) {
        $data = $response->json();
        echo "\n✅ API call successful!\n";
        
        if (isset($data['statusCode']) && $data['statusCode'] == 200) {
            echo "✅ Status code indicates success\n";
            
            if (isset($data['data'])) {
                $accountData = $data['data'];
                echo "\n4. Virtual Account Details:\n";
                echo "   Account Number: " . ($accountData['accountNumber'] ?? 'N/A') . "\n";
                echo "   Bank Name: " . ($accountData['bank'] ?? 'N/A') . "\n";
                echo "   Account Name: " . ($accountData['accountName'] ?? 'N/A') . "\n";
                echo "   Reference: " . ($accountData['reference'] ?? 'N/A') . "\n";
                echo "   Amount: " . ($accountData['amount'] ?? 'N/A') . "\n";
                
                if (!empty($accountData['accountNumber'])) {
                    echo "\n🎉 SUCCESS: Virtual account generation is working!\n";
                } else {
                    echo "\n⚠️  WARNING: Account number is empty in response\n";
                }
            } else {
                echo "\n❌ ERROR: No 'data' field in response\n";
                echo "   Full response: " . json_encode($data, JSON_PRETTY_PRINT) . "\n";
            }
        } else {
            echo "\n❌ ERROR: API returned error status\n";
            if (isset($data['message'])) {
                echo "   Error Message: " . $data['message'] . "\n";
            }
            echo "   Full response: " . json_encode($data, JSON_PRETTY_PRINT) . "\n";
        }
    } else {
        echo "\n❌ ERROR: API call failed\n";
        echo "   HTTP Status: " . $response->status() . "\n";
        echo "   Error: " . $response->body() . "\n";
        
        // Check if it's an HTML response (Laravel error page)
        if (strpos($response->body(), '<!DOCTYPE html>') !== false) {
            echo "\n⚠️  WARNING: Received HTML response instead of JSON\n";
            echo "   This suggests the API endpoint might be incorrect or there's an authentication issue\n";
        }
    }
    
} catch (\Exception $e) {
    echo "\n❌ EXCEPTION: " . $e->getMessage() . "\n";
    echo "   Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n5. Testing Laravel PayVibe ProcessController...\n";

try {
    // Create a test deposit with correct schema
    $deposit = new \App\Models\Deposit();
    $deposit->user_id = 1; // Test user
    $deposit->method_code = 120; // PayVibe method code
    $deposit->method_currency = 'NGN'; // PayVibe currency
    $deposit->amount = 1000.00;
    $deposit->charge = 15.00;
    $deposit->final_amo = 1015.00;
    $deposit->trx = 'TEST_' . time();
    $deposit->status = 0;
    $deposit->save();
    
    echo "✅ Test deposit created with ID: " . $deposit->id . "\n";
    
    // Test the process method
    $result = \App\Http\Controllers\Gateway\PayVibe\ProcessController::process($deposit);
    $resultData = json_decode($result, true);
    
    echo "\n6. ProcessController Result:\n";
    echo "   Raw Result: " . $result . "\n";
    
    if (isset($resultData['error']) && $resultData['error']) {
        echo "\n❌ ERROR: " . ($resultData['message'] ?? 'Unknown error') . "\n";
    } else {
        echo "\n✅ ProcessController executed successfully\n";
        
        if (isset($resultData['val']['virtual_account']) && !empty($resultData['val']['virtual_account'])) {
            echo "✅ Virtual account generated: " . $resultData['val']['virtual_account'] . "\n";
            echo "✅ Bank name: " . $resultData['val']['bank_name'] . "\n";
            echo "✅ Account name: " . $resultData['val']['account_name'] . "\n";
            echo "\n🎉 SUCCESS: PayVibe integration is working perfectly!\n";
        } else {
            echo "\n⚠️  WARNING: Virtual account not found in response\n";
        }
    }
    
    // Clean up test deposit
    $deposit->delete();
    echo "\n✅ Test deposit cleaned up\n";
    
} catch (\Exception $e) {
    echo "\n❌ EXCEPTION in ProcessController test: " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n";
echo "\nSummary:\n";
echo "- API Endpoint: $apiUrl\n";
echo "- Secret Key: " . substr($secretKey, 0, 10) . "...\n";
echo "- Test Reference: {$testData['reference']}\n";
echo "\nIf you see SUCCESS messages above, PayVibe account generation is working!\n";
?> 