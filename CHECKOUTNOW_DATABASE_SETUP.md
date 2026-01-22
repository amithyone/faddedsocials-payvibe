# CheckoutNow Database Setup Guide

## Overview
CheckoutNow (code: 121) is configured directly in the database and is **hidden from the admin panel** for security and simplicity, similar to XtraPay.

## Database Tables

### 1. `gateways` Table
Contains the main gateway configuration.

**Check if CheckoutNow exists:**
```sql
SELECT * FROM gateways WHERE code = 121;
```

**Expected Record:**
```sql
INSERT INTO `gateways` (
    `code`, 
    `name`, 
    `alias`, 
    `status`, 
    `gateway_parameters`, 
    `supported_currencies`, 
    `crypto`, 
    `description`, 
    `created_at`, 
    `updated_at`
) VALUES (
    121,
    'CheckoutNow',
    'CheckoutNow',
    1,
    '{"api_key":{"title":"API Key","global":true,"value":""}}',
    '{"NGN":{"symbol":"₦"}}',
    0,
    'CheckoutPay Payment Gateway (CheckoutNow)',
    NOW(),
    NOW()
);
```

### 2. `gateway_currencies` Table
Contains currency-specific configuration for CheckoutNow.

**Check if CheckoutNow currency exists:**
```sql
SELECT * FROM gateway_currencies WHERE method_code = 121;
```

**Expected Record:**
```sql
INSERT INTO `gateway_currencies` (
    `name`,
    `gateway_alias`,
    `currency`,
    `symbol`,
    `method_code`,
    `min_amount`,
    `max_amount`,
    `percent_charge`,
    `fixed_charge`,
    `rate`,
    `gateway_parameter`,
    `created_at`,
    `updated_at`
) VALUES (
    'CheckoutNow - NGN',
    'CheckoutNow',
    'NGN',
    '₦',
    121,
    100,
    1000000,
    1.0,
    50,
    1,
    '{"api_key":"YOUR_API_KEY_HERE"}',
    NOW(),
    NOW()
);
```

## Configuration Steps

### Step 1: Verify Gateway Exists
```sql
SELECT id, code, name, alias, status FROM gateways WHERE code = 121;
```

### Step 2: Verify Currency Configuration
```sql
SELECT 
    id,
    name,
    currency,
    min_amount,
    max_amount,
    fixed_charge,
    percent_charge,
    rate,
    gateway_parameter
FROM gateway_currencies 
WHERE method_code = 121;
```

### Step 3: Update API Key
```sql
UPDATE gateway_currencies 
SET gateway_parameter = JSON_SET(
    gateway_parameter,
    '$.api_key',
    'pk_your_actual_api_key_here'
)
WHERE method_code = 121;
```

### Step 4: Enable/Disable Gateway
```sql
-- Enable CheckoutNow
UPDATE gateways SET status = 1 WHERE code = 121;

-- Disable CheckoutNow
UPDATE gateways SET status = 0 WHERE code = 121;
```

### Step 5: Update Charges (if needed)
```sql
-- Update fixed charge
UPDATE gateway_currencies 
SET fixed_charge = 50 
WHERE method_code = 121;

-- Update percent charge
UPDATE gateway_currencies 
SET percent_charge = 1.0 
WHERE method_code = 121;

-- Update min/max amounts
UPDATE gateway_currencies 
SET min_amount = 100, max_amount = 1000000 
WHERE method_code = 121;
```

## Quick Status Check Query
```sql
SELECT 
    g.code,
    g.name,
    g.status AS gateway_status,
    gc.currency,
    gc.min_amount,
    gc.max_amount,
    gc.fixed_charge,
    gc.percent_charge,
    JSON_EXTRACT(gc.gateway_parameter, '$.api_key') AS api_key_configured
FROM gateways g
LEFT JOIN gateway_currencies gc ON g.code = gc.method_code
WHERE g.code = 121;
```

## Troubleshooting

### CheckoutNow not showing in user deposit form?
1. Verify gateway status is enabled (status = 1)
2. Verify gateway_currencies record exists
3. Check that currency is 'NGN'
4. Verify the gateway is enabled in the frontend code

### CheckoutNow showing in admin panel?
- This should NOT happen. Check that the exclusion is working:
```sql
-- This query should return 0 results when checking admin panel
SELECT * FROM gateways WHERE code IN (118, 121);
```

### API Key Issues?
```sql
-- Check current API key
SELECT 
    JSON_EXTRACT(gateway_parameter, '$.api_key') AS api_key
FROM gateway_currencies 
WHERE method_code = 121;
```

## Important Notes

1. **CheckoutNow is hidden from admin panel** - Configure it directly in the database
2. **Code: 121** - This is the unique identifier for CheckoutNow
3. **API Key** - Must be stored in `gateway_currencies.gateway_parameter` as JSON
4. **Status** - Set `gateways.status = 1` to enable, `0` to disable
5. **Charges** - Fixed: ₦50, Percent: 1.0% (configured in gateway_currencies table)

## Environment Variables
Make sure these are set in your `.env` file:
```env
CHECKOUTNOW_BASE_URL=https://check-outpay.com/api/v1
CHECKOUTNOW_API_KEY=pk_your_api_key_here
CHECKOUTNOW_WEBHOOK_URL=https://your-domain.com/ipn/checkoutnow
```

## Related Files
- Migration: `database/migrations/2024_01_22_000000_add_checkoutnow_gateway.php`
- Controller: `app/Http/Controllers/Gateway/CheckoutNow/ProcessController.php`
- View: `resources/views/templates/basic/user/payment/CheckoutNow.blade.php`
