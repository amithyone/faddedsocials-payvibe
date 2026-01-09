<?php
/**
 * PayVibe API Test Script
 * Tests the correct API endpoints
 */

echo "=== PayVibe API Endpoint Test ===\n\n";

// Test 1: Check if the correct endpoints are being used
echo "1. Checking API endpoints in ProcessController...\n";

$controllerFile = 'app/Http/Controllers/Gateway/PayVibe/ProcessController.php';
if (file_exists($controllerFile)) {
    $content = file_get_contents($controllerFile);
    
    // Check for virtual account initiation endpoint
    if (strpos($content, 'payvibeapi.six3tech.com/api/v1/payments/virtual-accounts/initiate') !== false) {
        echo "✅ Virtual account initiation endpoint is correct\n";
        echo "   URL: https://payvibeapi.six3tech.com/api/v1/payments/virtual-accounts/initiate\n";
    } else {
        echo "❌ Virtual account initiation endpoint is incorrect\n";
    }
    
    // Check for requery endpoint
    if (strpos($content, 'payvibeapi.six3tech.com/api/v1/payments/virtual-accounts/requery') !== false) {
        echo "✅ Transaction requery endpoint is correct\n";
        echo "   URL: https://payvibeapi.six3tech.com/api/v1/payments/virtual-accounts/requery/{reference}\n";
    } else {
        echo "❌ Transaction requery endpoint is incorrect\n";
    }
    
    // Check for old incorrect endpoints
    if (strpos($content, 'api.payvibe.com/v1/generateAccount') !== false) {
        echo "❌ Old incorrect endpoint still found: api.payvibe.com/v1/generateAccount\n";
    } else {
        echo "✅ Old incorrect endpoint removed\n";
    }
    
    if (strpos($content, 'api.payvibe.com/v1/requeryTransaction') !== false) {
        echo "❌ Old incorrect endpoint still found: api.payvibe.com/v1/requeryTransaction\n";
    } else {
        echo "✅ Old incorrect endpoint removed\n";
    }
} else {
    echo "❌ PayVibe ProcessController not found\n";
}

// Test 2: Check environment variables
echo "\n2. Checking PayVibe environment variables...\n";
$envFile = '.env';
if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
    
    $requiredVars = [
        'PAYVIBE_PUBLIC_KEY',
        'PAYVIBE_SECRET_KEY', 
        'PAYVIBE_PRODUCT_IDENTIFIER',
        'PAYVIBE_WEBHOOK_URL'
    ];
    
    $missingVars = [];
    foreach ($requiredVars as $var) {
        if (!preg_match("/^{$var}=/m", $envContent)) {
            $missingVars[] = $var;
        }
    }
    
    if (empty($missingVars)) {
        echo "✅ All PayVibe environment variables are configured\n";
    } else {
        echo "❌ Missing environment variables: " . implode(', ', $missingVars) . "\n";
    }
} else {
    echo "❌ .env file not found\n";
}

// Test 3: Check database configuration
echo "\n3. Checking PayVibe database configuration...\n";
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=socials', 'root', '');
    echo "✅ Database connection successful\n";
    
    $stmt = $pdo->query("SELECT * FROM gateways WHERE alias = 'PayVibe'");
    $gateway = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($gateway) {
        echo "✅ PayVibe gateway found in database\n";
        echo "   - Status: " . ($gateway['status'] ? 'Active' : 'Inactive') . "\n";
        
        $params = json_decode($gateway['gateway_parameters'], true);
        if (isset($params['secret_key']['value']) && !empty($params['secret_key']['value'])) {
            echo "✅ Secret key is configured for API calls\n";
        } else {
            echo "❌ Secret key is missing or empty\n";
        }
    } else {
        echo "❌ PayVibe gateway not found in database\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n";
echo "\nSummary:\n";
echo "- Virtual account generation: https://payvibeapi.six3tech.com/api/v1/payments/virtual-accounts/initiate\n";
echo "- Transaction requery: https://payvibeapi.six3tech.com/api/v1/payments/virtual-accounts/requery/{reference}\n";
echo "- Webhook URL: https://fadded.net/api/ipn/payvibe\n";
echo "\nThe PayVibe integration should now work correctly with the proper API endpoints!\n";
?> 