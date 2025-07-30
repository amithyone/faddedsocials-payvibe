# PayVibe Payment Gateway Integration

This document provides a comprehensive guide for integrating PayVibe payment gateway into your Laravel application.

## Overview

PayVibe is a Nigerian payment gateway that provides virtual account generation for seamless bank transfers. This integration allows users to make deposits through bank transfers using virtual accounts.

## Features

- Virtual account generation for bank transfers
- Automatic payment verification via webhooks
- Transaction status tracking
- Mobile-optimized payment interface
- Secure hash verification for webhooks
- Support for NGN currency

## Installation

### 1. Database Setup

Run the migration to add PayVibe gateway to your database:

```bash
php artisan migrate
```

Or run the seeder to add PayVibe gateway data:

```bash
php artisan db:seed --class=PayVibeGatewaySeeder
```

### 2. Environment Variables

Add the following environment variables to your `.env` file:

```env
# PayVibe Configuration
PAYVIBE_ACCESS_KEY=your_payvibe_access_key_here
PAYVIBE_WEBHOOK_URL=https://your-domain.com/webhook/payvibe
PAYVIBE_API_KEY=your_webhook_api_key
PAYVIBE_API_CODE=faddedsocials
```

### 3. Gateway Configuration

1. Go to your admin panel
2. Navigate to Payment Gateway settings
3. Find "PayVibe" in the list
4. Click "Edit" and configure:
   - Access Key: Your PayVibe access key
   - Status: Enable/Disable
   - Min Amount: 100 NGN
   - Max Amount: 1,000,000 NGN
   - Fixed Charge: 100 NGN
   - Percent Charge: 1.5%

## API Endpoints

### Webhook Endpoint
```
POST /api/ipn/payvibe
```

### Transaction Check Endpoint
```
GET /api/ipn/payvibe/requery/{reference}
```

## Payment Flow

### 1. Deposit Initiation
When a user initiates a deposit:

1. User selects PayVibe as payment method
2. System generates a unique 12-digit reference
3. PayVibe API is called to generate virtual account
4. User is shown payment details with virtual account information

### 2. Payment Processing
During payment:

1. User transfers money to the virtual account
2. PayVibe sends webhook notification to your server
3. System verifies the webhook signature
4. User balance is updated automatically
5. Transaction status is updated

### 3. Webhook Verification
The webhook includes:
- Transaction reference
- Amount received
- Payment status
- HMAC signature for security

## Security Features

### Hash Verification
All webhooks are verified using HMAC-SHA256:

```php
$computedHash = hash_hmac('sha256', json_encode($payload['data']), $accessKey);
if (!hash_equals($computedHash, $payload['hash'])) {
    // Invalid webhook
}
```

### Database Locking
Prevents race conditions during payment processing:

```php
$deposit = Deposit::where('trx', $reference)->lockForUpdate()->first();
```

## File Structure

```
app/
├── Http/Controllers/Gateway/PayVibe/
│   └── ProcessController.php          # Main payment processing
├── Services/
│   └── WebhookService.php            # Webhook handling
resources/views/templates/basic/user/payment/
└── PayVibe.blade.php                 # Payment interface
routes/
└── api.php                           # API routes
database/
├── migrations/
│   └── 2024_01_19_000000_add_payvibe_gateway.php
└── seeders/
    └── PayVibeGatewaySeeder.php
```

## Configuration

### Gateway Parameters
- `access_key`: Your PayVibe API access key
- `min_amount`: Minimum transaction amount (100 NGN)
- `max_amount`: Maximum transaction amount (1,000,000 NGN)
- `fixed_charge`: Fixed transaction fee (100 NGN)
- `percent_charge`: Percentage fee (1.5%)

### Webhook Configuration
- `PAYVIBE_WEBHOOK_URL`: Your webhook endpoint URL
- `PAYVIBE_API_KEY`: API key for webhook verification
- `PAYVIBE_API_CODE`: Your site's API code

## Error Handling

### Common Errors
1. **Invalid Authentication**: Webhook signature verification failed
2. **Transaction not found**: Reference number doesn't exist
3. **Amount mismatch**: Received amount differs from expected
4. **Already processed**: Transaction already completed

### Error Responses
```json
{
    "error": "Error message",
    "status_code": 400
}
```

## Testing

### Test Mode
For testing, use PayVibe's test environment:
- Test API URL: `https://test-api.payvibe.com/v1/`
- Test Access Key: Provided by PayVibe

### Test Transactions
1. Generate test virtual account
2. Make test transfer
3. Verify webhook processing
4. Check balance update

## Mobile Optimization

The payment interface is optimized for mobile devices with:
- Responsive design
- Touch-friendly buttons
- Copy-to-clipboard functionality
- Countdown timer for account expiration
- Mobile-optimized alerts

## Customization

### Branding
Update colors in `PayVibe.blade.php`:
```css
.copy-btn {
    background: #00be9c;  /* Your brand color */
}
```

### Styling
Modify the payment interface by editing:
- `resources/views/templates/basic/user/payment/PayVibe.blade.php`
- CSS styles in the `@push('style')` section

## Troubleshooting

### Common Issues

1. **Webhook not received**
   - Check webhook URL configuration
   - Verify server accessibility
   - Check firewall settings

2. **Payment not credited**
   - Verify webhook signature
   - Check database transaction logs
   - Confirm user balance update

3. **Virtual account generation failed**
   - Verify API access key
   - Check API endpoint URL
   - Confirm account limits

### Debugging
Enable logging in `config/logging.php`:
```php
'channels' => [
    'payvibe' => [
        'driver' => 'single',
        'path' => storage_path('logs/payvibe.log'),
        'level' => 'debug',
    ],
],
```

## Support

For technical support:
- Check application logs: `storage/logs/laravel.log`
- Verify webhook delivery in PayVibe dashboard
- Contact PayVibe support for API issues

## Changelog

### Version 1.0.0
- Initial PayVibe integration
- Virtual account generation
- Webhook processing
- Mobile-optimized interface
- Security features implementation

## License

This integration is part of your Laravel application and follows the same license terms. 