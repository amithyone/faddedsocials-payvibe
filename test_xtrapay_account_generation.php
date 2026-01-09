<?php
/**
 * XtraPay Account Generation Test
 * Tests if virtual account generation is working with the XtraPay API
 */

echo "=== XtraPay Account Generation Test ===\n\n";

// Load Laravel environment
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Http;

echo "1. Testing XtraPay API endpoint configuration...\n";

// Test the API endpoint directly
$apiUrl = 'https://mobile.xtrapay.ng/api/faddedsocials/generateAccount';
$accessKey = env('XTRAPAY_ACCESS_KEY');

if (empty($accessKey)) {
    echo "❌ XTRAPAY_ACCESS_KEY not found in .env\n";
    echo "   Please add XTRAPAY_ACCESS_KEY to your .env file\n";
    exit(1);
}

echo "✅ Access key found: " . substr($accessKey, 0, 10) . "...\n";
echo "✅ API URL: $apiUrl\n";

// Generate a unique 12-digit numeric reference (as per ProcessController)
$reference = str_pad(mt_rand(100000000000, 999999999999), 12, '0', STR_PAD_LEFT);

// Test data
$testData = [
    'reference' => $reference,
    'amount' => 1000.00,
    'service' => 'fadded_social'
];

echo "\n2. Testing API call with test data...\n";
echo "   Reference: {$testData['reference']}\n";
echo "   Amount: {$testData['amount']}\n";
echo "   Service: {$testData['service']}\n";

try {
    $response = Http::withToken($accessKey)
        ->timeout(30)
        ->post($apiUrl, $testData);
    
    echo "\n3. API Response:\n";
    echo "   Status Code: " . $response->status() . "\n";
    echo "   Response Body (first 500 chars): " . substr($response->body(), 0, 500) . "\n";
    
    if ($response->successful()) {
        $data = $response->json();
        echo "\n✅ API call successful!\n";
        echo "   Full Response: " . json_encode($data, JSON_PRETTY_PRINT) . "\n";
        
        if (isset($data['statusCode']) && $data['statusCode'] == 200) {
            echo "\n✅ Status code indicates success (200)\n";
            
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
                    echo "   ✅ Account number retrieved: " . $accountData['accountNumber'] . "\n";
                } else {
                    echo "\n⚠️  WARNING: Account number is empty in response\n";
                    echo "   Response structure: " . json_encode($accountData, JSON_PRETTY_PRINT) . "\n";
                }
            } else {
                echo "\n❌ ERROR: No 'data' field in response\n";
                echo "   Full response: " . json_encode($data, JSON_PRETTY_PRINT) . "\n";
            }
        } else {
            echo "\n❌ ERROR: API returned error status\n";
            if (isset($data['statusCode'])) {
                echo "   Status Code: " . $data['statusCode'] . "\n";
            }
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

echo "\n5. Testing Laravel XtraPay ProcessController...\n";

try {
    // Get the XtraPay gateway currency
    $gatewayCurrency = \App\Models\GatewayCurrency::whereHas('method', function($query) {
        $query->where('alias', 'Xtrapay');
    })->first();
    
    if (!$gatewayCurrency) {
        echo "⚠️  WARNING: XtraPay gateway currency not found in database\n";
        echo "   Skipping ProcessController test\n";
    } else {
        echo "✅ XtraPay gateway currency found (ID: {$gatewayCurrency->id})\n";
        
        // Create a test deposit
        $deposit = new \App\Models\Deposit();
        $deposit->user_id = 1; // Test user
        $deposit->method_code = $gatewayCurrency->method_code;
        $deposit->method_currency = $gatewayCurrency->currency;
        $deposit->amount = 1000.00;
        $deposit->charge = 15.00;
        $deposit->final_amo = 1015.00;
        $deposit->trx = 'TEST_' . time() . '_' . mt_rand(1000, 9999);
        $deposit->status = 0;
        $deposit->save();
        
        echo "✅ Test deposit created with ID: " . $deposit->id . "\n";
        echo "   Transaction Reference: {$deposit->trx}\n";
        
        // Test the process method
        $result = \App\Http\Controllers\Gateway\Xtrapay\ProcessController::process($deposit);
        $resultData = json_decode($result, true);
        
        echo "\n6. ProcessController Result:\n";
        
        if (isset($resultData['error']) && $resultData['error']) {
            echo "\n❌ ERROR: " . ($resultData['message'] ?? 'Unknown error') . "\n";
        } else {
            echo "\n✅ ProcessController executed successfully\n";
            
            if (isset($resultData['val']['virtual_account']) && !empty($resultData['val']['virtual_account'])) {
                echo "✅ Virtual account generated: " . $resultData['val']['virtual_account'] . "\n";
                echo "✅ Bank name: " . ($resultData['val']['bank_name'] ?? 'N/A') . "\n";
                echo "✅ Account name: " . ($resultData['val']['account_name'] ?? 'N/A') . "\n";
                echo "✅ Amount: " . ($resultData['val']['amount'] ?? 'N/A') . "\n";
                echo "✅ Reference: " . ($resultData['val']['reference'] ?? 'N/A') . "\n";
                echo "\n🎉 SUCCESS: XtraPay integration is working perfectly!\n";
            } else {
                echo "\n⚠️  WARNING: Virtual account not found in response\n";
                echo "   Response structure: " . json_encode($resultData, JSON_PRETTY_PRINT) . "\n";
            }
        }
        
        // Clean up test deposit
        $deposit->delete();
        echo "\n✅ Test deposit cleaned up\n";
    }
    
} catch (\Exception $e) {
    echo "\n❌ EXCEPTION in ProcessController test: " . $e->getMessage() . "\n";
    echo "   Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== Test Complete ===\n";
echo "\nSummary:\n";
echo "- API Endpoint: $apiUrl\n";
echo "- Access Key: " . substr($accessKey, 0, 10) . "...\n";
echo "- Test Reference: $reference\n";
echo "\nIf you see SUCCESS messages above, XtraPay account generation is working!\n";
?>

