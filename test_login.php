<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Login Authentication Test ===\n\n";

// Test 1: Check if LoginController exists
echo "1. Testing LoginController...\n";
try {
    $controller = new App\Http\Controllers\User\Auth\LoginController();
    echo "   ✓ LoginController instantiated\n";
    echo "   ✓ Has attemptLogin: " . (method_exists($controller, 'attemptLogin') ? 'Yes' : 'No') . "\n";
    echo "   ✓ Has guard: " . (method_exists($controller, 'guard') ? 'Yes' : 'No') . "\n";
} catch (Exception $e) {
    echo "   ✗ ERROR: " . $e->getMessage() . "\n";
}

// Test 2: Test credentials method
echo "\n2. Testing credentials method...\n";
try {
    $request = new Illuminate\Http\Request();
    $request->merge(['username' => 'test@test.com', 'password' => 'test123']);
    $controller = new App\Http\Controllers\User\Auth\LoginController();
    $controller->findUsername();
    echo "   Username field: " . $controller->username() . "\n";
    $creds = $controller->credentials($request);
    echo "   ✓ Credentials extracted: " . json_encode(array_keys($creds)) . "\n";
} catch (Exception $e) {
    echo "   ✗ ERROR: " . $e->getMessage() . "\n";
}

// Test 3: Test user retrieval
echo "\n3. Testing user retrieval...\n";
try {
    $user = App\Models\User::where('username', 'Olenaposs')->orWhere('email', 'Olenaposs')->first();
    if ($user) {
        echo "   ✓ User found: " . $user->username . "\n";
        echo "   ✓ User ID: " . $user->id . "\n";
        echo "   ✓ Password hash exists: " . (strlen($user->password) > 0 ? 'Yes' : 'No') . "\n";
    } else {
        echo "   ✗ User not found\n";
    }
} catch (Exception $e) {
    echo "   ✗ ERROR: " . $e->getMessage() . "\n";
}

// Test 4: Test guard attempt
echo "\n4. Testing authentication guard...\n";
try {
    $guard = Auth::guard('web');
    echo "   ✓ Guard retrieved: " . get_class($guard) . "\n";
    
    $provider = $guard->getProvider();
    echo "   ✓ Provider: " . get_class($provider) . "\n";
    
    $user = $provider->retrieveByCredentials(['username' => 'Olenaposs']);
    if ($user) {
        echo "   ✓ User retrieved by credentials: " . $user->username . "\n";
    } else {
        echo "   ✗ User not retrieved by credentials\n";
    }
} catch (Exception $e) {
    echo "   ✗ ERROR: " . $e->getMessage() . "\n";
}

// Test 5: Check session
echo "\n5. Testing session configuration...\n";
try {
    $driver = config('session.driver');
    $lifetime = config('session.lifetime');
    echo "   ✓ Session driver: $driver\n";
    echo "   ✓ Session lifetime: $lifetime minutes\n";
    
    $sessionPath = storage_path('framework/sessions');
    echo "   ✓ Session path: $sessionPath\n";
    echo "   ✓ Session path writable: " . (is_writable($sessionPath) ? 'Yes' : 'No') . "\n";
} catch (Exception $e) {
    echo "   ✗ ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n";
