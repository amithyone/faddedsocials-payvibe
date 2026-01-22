# CheckoutNow (CheckoutPay) Payment Gateway Integration

This document provides a comprehensive guide for integrating CheckoutNow payment gateway into your Laravel application.

## Overview

CheckoutNow (powered by CheckoutPay) is a Nigerian payment gateway that provides virtual account generation for seamless bank transfers. This integration allows users to make deposits through bank transfers using virtual accounts.

## Features

- Virtual account generation for bank transfers
- Automatic payment verification via webhooks
- Transaction status tracking
- Mobile-optimized payment interface
- Secure API key authentication for webhooks
- Support for NGN currency

## Installation

### 1. Database Setup

Run the migration to add CheckoutNow gateway to your database:

```bash
php artisan migrate
```

Or manually run the SQL:

```sql
-- Insert CheckoutNow gateway
INSERT INTO `gateways` (`code`, `name`, `alias`, `status`, `gateway_parameters`, `supported_currencies`, `crypto`, `description`, `created_at`, `updated_at`) 
VALUES (121, 'CheckoutNow', 'CheckoutNow', 1, '{"api_key":{"title":"API Key","global":true,"value":""}}', '{"NGN":{"symbol":"₦"}}', 0, 'CheckoutPay Payment Gateway (CheckoutNow)', NOW(), NOW());

-- Insert gateway currency
INSERT INTO `gateway_currencies` (`name`, `gateway_alias`, `currency`, `symbol`, `method_code`, `min_amount`, `max_amount`, `percent_charge`, `fixed_charge`, `rate`, `gateway_parameter`, `created_at`, `updated_at`) 
VALUES ('CheckoutNow - NGN', 'CheckoutNow', 'NGN', '₦', 121, 100, 1000000, 1.0, 50, 1, '{"api_key":""}', NOW(), NOW());
```

### 2. Environment Variables

Add the following environment variables to your `.env` file:

```env
# CheckoutNow Configuration
CHECKOUTNOW_BASE_URL=https://check-outpay.com/api/v1
CHECKOUTNOW_API_KEY=pk_your_api_key_here
CHECKOUTNOW_WEBHOOK_URL=https://your-domain.com/ipn/checkoutnow
```

**Important:** 
- Replace `pk_your_api_key_here` with your actual CheckoutPay API key from your dashboard
- Replace `https://your-domain.com` with your actual domain
- The webhook URL must be from an approved website domain in your CheckoutPay dashboard

### 3. Gateway Configuration

1. Go to your admin panel
2. Navigate to Payment Gateway settings
3. Find "CheckoutNow" in the list
4. Click "Edit" and configure:
   - **API Key**: Your CheckoutPay API key (starts with `pk_`)
   - **Status**: Enable/Disable
   - **Min Amount**: 100 NGN
   - **Max Amount**: 1,000,000 NGN
   - **Fixed Charge**: 50 NGN
   - **Percent Charge**: 1.0%

## API Endpoints

### Webhook Endpoint
```
POST /ipn/checkoutnow
```

### Transaction Check Endpoint
```
GET /ipn/checkoutnow/requery/{transactionId}
```

## Payment Flow

### 1. Deposit Initiation
When a user initiates a deposit:

1. User selects CheckoutNow as payment method
2. System generates a unique transaction ID (format: `TXN-{timestamp}-{random}`)
3. CheckoutPay API is called to create payment request
4. User is shown payment details with virtual account information

### 2. Payment Processing
During payment:

1. User transfers money to the virtual account
2. CheckoutPay detects the payment
3. Webhook is sent to your server with `payment.approved` event
4. System verifies the transaction
5. User's wallet is automatically credited

### 3. Webhook Processing
The webhook handler:

1. Validates the API key (if provided)
2. Extracts transaction details from payload
3. Finds the deposit record
4. Updates user balance
5. Marks deposit as successful
6. Sends notifications

## Webhook Payload Structure

When a payment is approved, you'll receive a POST request with:

```json
{
  "event": "payment.approved",
  "transaction_id": "TXN-1234567890",
  "status": "approved",
  "amount": 5000.00,
  "received_amount": 5000.00,
  "payer_name": "John Doe",
  "bank": "GTBank",
  "account_number": "0123456789",
  "charges": {
    "percentage": 50.00,
    "fixed": 50.00,
    "total": 100.00,
    "paid_by_customer": false,
    "business_receives": 4900.00
  },
  "matched_at": "2024-01-15T10:30:00Z",
  "approved_at": "2024-01-15T10:35:00Z",
  "timestamp": "2024-01-15T10:35:00Z"
}
```

## Code Structure

### Files Created

1. **ProcessController**: `app/Http/Controllers/Gateway/CheckoutNow/ProcessController.php`
   - Handles payment request creation
   - Processes webhook notifications
   - Manages transaction verification

2. **Migration**: `database/migrations/2024_01_22_000000_add_checkoutnow_gateway.php`
   - Adds gateway to database
   - Sets up gateway currency

3. **View Template**: `resources/views/templates/basic/user/payment/CheckoutNow.blade.php`
   - Mobile-optimized payment interface
   - Copy-to-clipboard functionality
   - Countdown timer for account expiration

4. **Routes**: Added to `routes/ipn.php`
   - Webhook endpoint
   - Transaction check endpoint

5. **Configuration**: Updated `config/services.php`
   - CheckoutNow service configuration

## Testing

### Test Payment Request

1. Create a test deposit via the user dashboard
2. Select CheckoutNow as payment method
3. Verify that account details are displayed correctly
4. Check that transaction ID is generated

### Test Webhook

You can test the webhook endpoint using curl:

```bash
curl -X POST https://your-domain.com/ipn/checkoutnow \
  -H "Content-Type: application/json" \
  -H "X-API-Key: your_api_key" \
  -d '{
    "event": "payment.approved",
    "transaction_id": "TXN-TEST-123",
    "status": "approved",
    "amount": 5000.00,
    "received_amount": 5000.00
  }'
```

## Troubleshooting

### Issue: "Gateway parameter missing or invalid"
**Solution**: Ensure the API key is configured in the admin panel under Payment Gateway settings.

### Issue: "Webhook URL must be from your approved website domain"
**Solution**: Add your website URL to the approved websites list in your CheckoutPay dashboard.

### Issue: "Invalid API key"
**Solution**: Verify that your API key is correct and starts with `pk_` for public key.

### Issue: Payment not being credited
**Solution**: 
1. Check webhook logs in `storage/logs/laravel.log`
2. Verify the webhook URL is accessible
3. Ensure the transaction ID matches the deposit record

## Security Considerations

1. **API Key Security**: Never expose your API key in client-side code
2. **Webhook Validation**: Always validate webhook requests (API key verification is optional but recommended)
3. **HTTPS**: Ensure all webhook URLs use HTTPS
4. **Idempotency**: The system prevents duplicate processing of successful transactions

## Support

For CheckoutPay API documentation, visit: https://check-outpay.com/api-docs

For issues with this integration, check the Laravel logs:
```bash
tail -f storage/logs/laravel.log
```

## Gateway Code

- **Code**: 121
- **Name**: CheckoutNow
- **Alias**: CheckoutNow
- **Currency**: NGN (Nigerian Naira)
- **Min Amount**: 100 NGN
- **Max Amount**: 1,000,000 NGN
- **Fixed Charge**: 50 NGN
- **Percent Charge**: 1.0%
