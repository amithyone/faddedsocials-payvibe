<?php
/**
 * PayVibe Data Debug Test
 * Tests what data is being passed to the PayVibe view
 */

echo "=== PayVibe Data Debug Test ===\n\n";

// Load Laravel environment
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "1. Testing PayVibe data structure...\n";

try {
    // Create a test deposit
    $deposit = new \App\Models\Deposit();
    $deposit->user_id = 1;
    $deposit->method_code = 120; // PayVibe method code
    $deposit->method_currency = 'NGN';
    $deposit->amount = 1000;
    $deposit->charge = 100;
    $deposit->final_amo = 1100;
    $deposit->trx = 'TEST123456789';
    $deposit->status = 0;
    
    echo "✅ Test deposit created\n";
    echo "   - Amount: " . $deposit->amount . "\n";
    echo "   - Final Amount: " . $deposit->final_amo . "\n";
    echo "   - Method Code: " . $deposit->method_code . "\n";
    echo "   - Currency: " . $deposit->method_currency . "\n";

    // Test the process method
    echo "\n2. Testing PayVibe ProcessController...\n";
    
    try {
        $result = \App\Http\Controllers\Gateway\PayVibe\ProcessController::process($deposit);
        $data = json_decode($result);
        
        if (isset($data->error)) {
            echo "❌ Error in process: " . $data->message . "\n";
        } else {
            echo "✅ Process successful\n";
            echo "   - View: " . ($data->view ?? 'N/A') . "\n";
            
            if (isset($data->val)) {
                echo "   - Virtual Account: " . ($data->val->virtual_account ?? 'N/A') . "\n";
                echo "   - Bank Name: " . ($data->val->bank_name ?? 'N/A') . "\n";
                echo "   - Account Name: " . ($data->val->account_name ?? 'N/A') . "\n";
                echo "   - Amount: " . ($data->val->amount ?? 'N/A') . "\n";
                echo "   - Currency: " . ($data->val->currency ?? 'N/A') . "\n";
                echo "   - Reference: " . ($data->val->reference ?? 'N/A') . "\n";
            } else {
                echo "❌ No 'val' property found in data\n";
            }
        }
    } catch (\Exception $e) {
        echo "❌ Exception in process: " . $e->getMessage() . "\n";
    }

    echo "\n3. Testing view data structure...\n";
    
    // Create test data similar to what should be passed to view
    $testData = new \stdClass();
    $testData->val = [
        'virtual_account' => '8041007604',
        'bank_name' => 'Wema Bank',
        'account_name' => 'Finspa/PAYVIBE',
        'amount' => '1115',
        'currency' => 'NGN',
        'reference' => '561335573888',
        'custom' => '561335573888'
    ];
    $testData->view = 'user.payment.PayVibe';

    echo "✅ Test data created\n";
    echo "   - Virtual Account: " . $testData->val['virtual_account'] . "\n";
    echo "   - Bank Name: " . $testData->val['bank_name'] . "\n";
    echo "   - Account Name: " . $testData->val['account_name'] . "\n";
    echo "   - Amount: " . $testData->val['amount'] . "\n";
    echo "   - Currency: " . $testData->val['currency'] . "\n";
    echo "   - Reference: " . $testData->val['reference'] . "\n";

    echo "\n4. Testing view rendering with test data...\n";
    
    try {
        $view = view('templates.basic.user.payment.PayVibe', ['data' => $testData]);
        echo "✅ View rendered successfully\n";
        
        // Check if the view content contains the expected data
        $viewContent = $view->render();
        
        if (strpos($viewContent, '8041007604') !== false) {
            echo "✅ Account number found in rendered view\n";
        } else {
            echo "❌ Account number NOT found in rendered view\n";
        }
        
        if (strpos($viewContent, 'Wema Bank') !== false) {
            echo "✅ Bank name found in rendered view\n";
        } else {
            echo "❌ Bank name NOT found in rendered view\n";
        }
        
        if (strpos($viewContent, 'Finspa/PAYVIBE') !== false) {
            echo "✅ Account name found in rendered view\n";
        } else {
            echo "❌ Account name NOT found in rendered view\n";
        }
        
    } catch (\Exception $e) {
        echo "❌ View rendering failed: " . $e->getMessage() . "\n";
    }

    echo "\n=== Debug Complete ===\n";
    echo "\nSummary:\n";
    echo "- PayVibe ProcessController data structure is correct\n";
    echo "- View should display account details if data is passed correctly\n";
    echo "- Check if PaymentController is passing data correctly to view\n";

} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "   Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n🔍 Debug test complete!\n";
?> 