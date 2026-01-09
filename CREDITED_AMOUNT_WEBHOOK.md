# Credited Amount Webhook to Xtrabusiness

## Overview

This feature sends detailed information about the amount credited to user's balance to Xtrabusiness for tracking and reconciliation purposes.

## What Information is Sent

### For Successful Transactions
When a payment is successful, the following information is sent to Xtrabusiness:

1. **Credited Amount**: The actual amount added to user's balance (after charges)
2. **Total Paid**: The total amount paid by the user
3. **Charges**: Transaction fees deducted
4. **User Balance**: Before and after credit amounts
5. **Payment Method**: Whether it's PayVibe or Xtrapay
6. **Transaction Details**: Reference, timestamps, user information

### Webhook Payload Structure

```json
{
    "site_api_code": "faddedsocials",
    "reference": "TRX123456789",
    "credited_amount": 950.00,
    "total_paid": 1000.00,
    "charges": 50.00,
    "currency": "NGN",
    "status": "credited",
    "payment_method": "payvibe",
    "customer_email": "user@example.com",
    "customer_name": "John Doe",
    "description": "Amount credited to user balance",
    "external_id": "123",
    "metadata": {
        "deposit_id": 123,
        "user_id": 456,
        "credited_amount": 950.00,
        "total_paid": 1000.00,
        "charges": 50.00,
        "final_amount": 1000.00,
        "charge": 50.00,
        "payment_reference": "TRX123456789",
        "site_name": "faddedsocials.com",
        "site_url": "https://faddedsocials.com",
        "user_balance_before": 500.00,
        "user_balance_after": 1450.00,
        "credit_timestamp": "2024-01-19T10:30:00Z",
        "transaction_type": "credit"
    },
    "timestamp": "2024-01-19T10:30:00Z"
}
```

## Implementation Details

### 1. Webhook Service Enhancement

The `WebhookService` class has been enhanced with:

- **Enhanced existing methods**: `sendToXtrabusiness()` and `sendToPayVibe()` now include credited amount information
- **New dedicated method**: `sendCreditedAmountToXtrabusiness()` for specific credited amount tracking

### 2. Payment Controllers Update

Both PayVibe and Xtrapay ProcessControllers now call:

```php
// Send credited amount information to Xtrabusiness
WebhookService::sendCreditedAmountToXtrabusiness($deposit, $user);
```

### 3. Data Flow

1. **Payment Success**: When a payment is successful
2. **Balance Update**: User's balance is incremented
3. **Webhook Sent**: Credited amount information is sent to Xtrabusiness
4. **Logging**: All activities are logged for monitoring

## Configuration

### Environment Variables

Ensure these are set in your `.env` file:

```env
XTRABUSINESS_WEBHOOK_URL=https://xtrabusiness.com/webhook
XTRABUSINESS_API_KEY=your_api_key_here
XTRABUSINESS_API_CODE=faddedsocials
```

### Gateway Configuration

The webhook automatically detects the payment method:
- **PayVibe**: `payment_method` = "payvibe"
- **Xtrapay**: `payment_method` = "xtrapay"

## Monitoring and Logging

### Log Entries

The system logs detailed information:

```php
Log::info('Xtrabusiness credited amount webhook sent', [
    'deposit_id' => $deposit->id,
    'credited_amount' => $creditedAmount,
    'status_code' => $response->status(),
    'response' => $response->json(),
    'payload' => $payload
]);
```

### Error Handling

- **Webhook failures** are logged with error details
- **Network timeouts** are handled gracefully
- **Invalid responses** are logged for debugging

## Security Features

### Data Protection

- **HMAC verification** for webhook authenticity
- **Secure API keys** for authentication
- **Encrypted transmission** over HTTPS

### Validation

- **Amount validation** to prevent incorrect credits
- **User verification** to ensure valid transactions
- **Reference uniqueness** to prevent duplicates

## Use Cases

### 1. Financial Reconciliation

Xtrabusiness can use this data to:
- Reconcile payments with user credits
- Track transaction fees and charges
- Monitor payment method distribution

### 2. User Support

Support teams can:
- Verify user balance changes
- Track payment history
- Resolve payment disputes

### 3. Analytics

Business intelligence can:
- Analyze payment patterns
- Monitor charge structures
- Track user behavior

## Testing

### Test Scenarios

1. **Successful Payment**: Verify credited amount is sent
2. **Failed Payment**: Ensure no credit webhook is sent
3. **Partial Payment**: Handle amount mismatches
4. **Network Issues**: Test timeout and retry logic

### Test Data

```php
// Test payload structure
$testPayload = [
    'credited_amount' => 950.00,
    'total_paid' => 1000.00,
    'charges' => 50.00,
    'user_balance_before' => 500.00,
    'user_balance_after' => 1450.00
];
```

## Troubleshooting

### Common Issues

1. **Webhook Not Sent**
   - Check environment variables
   - Verify API key configuration
   - Check network connectivity

2. **Incorrect Amounts**
   - Verify deposit calculations
   - Check charge calculations
   - Validate balance updates

3. **Duplicate Webhooks**
   - Check transaction status
   - Verify webhook deduplication
   - Review logging for duplicates

### Debug Steps

1. **Check Logs**: Review `storage/logs/laravel.log`
2. **Verify Configuration**: Confirm environment variables
3. **Test Connectivity**: Ping webhook URL
4. **Review Payload**: Check webhook payload structure

## Performance Considerations

### Optimization

- **Async Processing**: Webhooks are sent asynchronously
- **Timeout Handling**: 30-second timeout for webhooks
- **Error Recovery**: Graceful failure handling
- **Memory Management**: Efficient payload construction

### Scalability

- **Stateless Design**: No session dependencies
- **Efficient Logging**: Structured log entries
- **Resource Management**: Minimal memory footprint

## Future Enhancements

### Planned Features

1. **Retry Mechanism**: Automatic webhook retry on failure
2. **Queue Processing**: Background job processing
3. **Webhook Analytics**: Success/failure rate monitoring
4. **Custom Endpoints**: Multiple webhook destinations

### Configuration Options

- **Webhook URLs**: Multiple endpoint support
- **Retry Logic**: Configurable retry attempts
- **Timeout Settings**: Adjustable timeout values
- **Logging Levels**: Configurable log verbosity

## Support

For technical support:
- Check application logs for error details
- Verify webhook delivery in Xtrabusiness dashboard
- Review network connectivity and firewall settings
- Contact development team for complex issues

---

**Status**: ✅ Implemented and Active
**Security**: ✅ HMAC Verified
**Monitoring**: ✅ Comprehensive Logging
**Documentation**: ✅ Complete 