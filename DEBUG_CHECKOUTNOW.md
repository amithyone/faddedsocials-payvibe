# Debug CheckoutNow Not Showing

## Step 1: Verify Database Records

Run these SQL queries to check if everything is set up correctly:

```sql
-- Check gateway record
SELECT * FROM gateways WHERE code = 121;

-- Check gateway_currency record
SELECT * FROM gateway_currencies WHERE method_code = 121;

-- Check both together
SELECT 
    g.code,
    g.name,
    g.status AS gateway_status,
    gc.id AS currency_id,
    gc.name AS currency_name,
    gc.status AS currency_status,
    gc.currency,
    gc.method_code
FROM gateways g
LEFT JOIN gateway_currencies gc ON g.code = gc.method_code
WHERE g.code = 121;
```

**Expected Results:**
- `gateway_status` should be `1`
- `currency_status` should be `1`
- `method_code` should be `121`
- Both records should exist

## Step 2: Fix if Missing

If records are missing or status is wrong:

```sql
-- Enable gateway
UPDATE gateways SET status = 1 WHERE code = 121;

-- Enable gateway currency
UPDATE gateway_currencies SET status = 1 WHERE method_code = 121;

-- If gateway_currency doesn't exist, create it:
INSERT INTO gateway_currencies (
    name, gateway_alias, currency, symbol, method_code,
    min_amount, max_amount, percent_charge, fixed_charge, 
    rate, status, gateway_parameter, created_at, updated_at
) VALUES (
    'CheckoutNow - NGN', 'CheckoutNow', 'NGN', '₦', 121,
    100, 1000000, 1.0, 50, 1, 1,
    '{"api_key":""}', NOW(), NOW()
);
```

## Step 3: Clear Cache

After fixing database, clear all caches:

```bash
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
composer dump-autoload
```

## Step 4: Test Query

Test the exact query the controller uses:

```php
// Run this in tinker: php artisan tinker
use App\Models\GatewayCurrency;
use App\Constants\Status;

$gateway_currency = GatewayCurrency::where('status', 1)
    ->whereHas('method', function ($query) {
        $query->where('status', Status::ENABLE);
    })
    ->get();

// Check if CheckoutNow is in the results
$checkoutnow = $gateway_currency->where('method_code', 121)->first();
dd($checkoutnow);
```

## Common Issues:

1. **Gateway status is 0** - Update: `UPDATE gateways SET status = 1 WHERE code = 121;`
2. **Currency status is 0** - Update: `UPDATE gateway_currencies SET status = 1 WHERE method_code = 121;`
3. **Record doesn't exist** - Run the seeder or insert manually
4. **Cache issue** - Clear all caches
5. **method_code type mismatch** - Ensure it's stored as integer, not string
