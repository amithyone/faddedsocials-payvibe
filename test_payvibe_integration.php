<?php
/**
 * PayVibe Integration Test Script
 * This script tests all aspects of the PayVibe integration
 */

echo "=== PayVibe Integration Test ===\n\n";

// Test 1: Check .env configuration
echo "1. Checking .env configuration...\n";
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

// Test 2: Check database connection
echo "\n2. Testing database connection...\n";
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=socials', 'root', '');
    echo "✅ Database connection successful\n";
    
    // Test 3: Check if PayVibe gateway exists
    echo "\n3. Checking PayVibe gateway in database...\n";
    $stmt = $pdo->query("SELECT * FROM gateways WHERE alias = 'PayVibe'");
    $gateway = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($gateway) {
        echo "✅ PayVibe gateway found in database\n";
        echo "   - Name: {$gateway['name']}\n";
        echo "   - Code: {$gateway['code']}\n";
        echo "   - Status: {$gateway['status']}\n";
        
        // Check gateway parameters
        $params = json_decode($gateway['gateway_parameters'], true);
        if (isset($params['public_key']['value']) && !empty($params['public_key']['value'])) {
            echo "✅ Public key is configured\n";
        } else {
            echo "❌ Public key is missing or empty\n";
        }
        
        if (isset($params['secret_key']['value']) && !empty($params['secret_key']['value'])) {
            echo "✅ Secret key is configured\n";
        } else {
            echo "❌ Secret key is missing or empty\n";
        }
    } else {
        echo "❌ PayVibe gateway not found in database\n";
    }
    
    // Test 4: Check PayVibe currency
    echo "\n4. Checking PayVibe currency...\n";
    $stmt = $pdo->query("SELECT * FROM gateway_currencies WHERE gateway_alias = 'PayVibe'");
    $currency = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($currency) {
        echo "✅ PayVibe currency found\n";
        echo "   - Currency: {$currency['currency']}\n";
        echo "   - Method Code: {$currency['method_code']}\n";
        echo "   - Min Amount: {$currency['min_amount']}\n";
        echo "   - Max Amount: {$currency['max_amount']}\n";
    } else {
        echo "❌ PayVibe currency not found\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    echo "   Please check your database credentials in .env file\n";
}

// Test 5: Check Laravel routes
echo "\n5. Checking PayVibe routes...\n";
$routesFile = 'routes/api.php';
if (file_exists($routesFile)) {
    $routesContent = file_get_contents($routesFile);
    if (strpos($routesContent, 'payvibe') !== false) {
        echo "✅ PayVibe routes found in api.php\n";
    } else {
        echo "❌ PayVibe routes not found in api.php\n";
    }
} else {
    echo "❌ routes/api.php not found\n";
}

// Test 6: Check PayVibe controller
echo "\n6. Checking PayVibe controller...\n";
$controllerFile = 'app/Http/Controllers/Gateway/PayVibe/ProcessController.php';
if (file_exists($controllerFile)) {
    echo "✅ PayVibe ProcessController exists\n";
    
    $controllerContent = file_get_contents($controllerFile);
    if (strpos($controllerContent, 'process') !== false) {
        echo "✅ Process method exists\n";
    } else {
        echo "❌ Process method not found\n";
    }
    
    if (strpos($controllerContent, 'ipn') !== false) {
        echo "✅ IPN method exists\n";
    } else {
        echo "❌ IPN method not found\n";
    }
} else {
    echo "❌ PayVibe ProcessController not found\n";
}

// Test 7: Check PayVibe view
echo "\n7. Checking PayVibe view...\n";
$viewFile = 'resources/views/templates/basic/user/payment/PayVibe.blade.php';
if (file_exists($viewFile)) {
    echo "✅ PayVibe payment view exists\n";
} else {
    echo "❌ PayVibe payment view not found\n";
}

// Test 8: Check WebhookService
echo "\n8. Checking WebhookService...\n";
$webhookFile = 'app/Services/WebhookService.php';
if (file_exists($webhookFile)) {
    $webhookContent = file_get_contents($webhookFile);
    if (strpos($webhookContent, 'payvibe') !== false) {
        echo "✅ PayVibe webhook methods exist\n";
    } else {
        echo "❌ PayVibe webhook methods not found\n";
    }
} else {
    echo "❌ WebhookService not found\n";
}

echo "\n=== Test Complete ===\n";
echo "\nNext Steps:\n";
echo "1. Fix any database connection issues\n";
echo "2. Run the PayVibe setup SQL script\n";
echo "3. Clear Laravel cache: php artisan config:clear\n";
echo "4. Test the payment flow on your website\n";
echo "5. Check logs for any errors\n";
?> 