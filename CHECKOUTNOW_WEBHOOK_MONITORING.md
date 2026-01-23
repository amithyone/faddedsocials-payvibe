# CheckoutNow Webhook Monitoring Guide

## 🔗 Webhook URL

The CheckoutNow webhook endpoint is exposed at:

```
POST https://fadded.net/ipn/checkoutnow
```

Or using the full API path:

```
POST https://fadded.net/api/ipn/checkoutnow
```

**Note:** Both URLs should work. The route is defined in `routes/ipn.php` as:
```php
Route::post('checkoutnow', 'CheckoutNow\ProcessController@ipn')->name('CheckoutNow');
```

## 📋 Webhook Configuration

### In CheckoutNow Dashboard

Set the webhook URL in your CheckoutNow dashboard to:
```
https://fadded.net/ipn/checkoutnow
```

### Environment Variables

The webhook URL is configured in `config/services.php`:
```php
'checkoutnow' => [
    'base_url' => env('CHECKOUTNOW_BASE_URL', 'https://check-outpay.com/api/v1'),
    'api_key' => env('CHECKOUTNOW_API_KEY'),
    'webhook_url' => env('CHECKOUTNOW_WEBHOOK_URL', env('APP_URL') . '/ipn/checkoutnow'),
],
```

## 🔍 Monitoring Webhook Activity

### Real-time Log Monitoring

To watch CheckoutNow webhooks in real-time:

```bash
# On the server
tail -f /home/wolrdhome/public_html/core/storage/logs/laravel.log | grep -i "CheckoutNow IPN"
```

### Check Recent Webhook Activity

```bash
# View last 100 lines with CheckoutNow activity
tail -n 500 /home/wolrdhome/public_html/core/storage/logs/laravel.log | grep -i "CheckoutNow IPN" | tail -n 50
```

## 📊 What Gets Logged

The enhanced logging now captures:

### 1. **Incoming Webhook**
- Headers (including API key if provided)
- Raw request body
- Parsed JSON payload
- All request data

### 2. **Payload Structure**
- Event type (`payment.approved`, `payment.rejected`, etc.)
- Transaction ID
- Status
- Full payload structure

### 3. **Deposit Lookup**
- Deposit ID found
- Current deposit status
- Expected vs received status
- Amount details (expected vs received)

### 4. **Balance Crediting**
- User ID
- Amount to credit
- Balance before crediting
- Balance after crediting
- Confirmation of successful credit

### 5. **Webhook Processing**
- When webhooks are sent to Xtrabusiness
- Transaction commit status
- Any errors or exceptions

## 🐛 Debugging Issues

### Common Issues and Logs to Check

#### 1. **Webhook Not Received**
- Check: `CheckoutNow IPN received` logs
- If missing: Verify webhook URL in CheckoutNow dashboard
- Check server firewall/security settings

#### 2. **Deposit Not Found**
- Check: `CheckoutNow IPN: Deposit not found` logs
- Verify: Transaction ID matches `deposits.trx` field
- Check: Transaction ID format in payload

#### 3. **Balance Not Credited**
- Check: `CheckoutNow IPN: User balance credited` logs
- Verify: Balance before/after amounts
- Check: `CheckoutNow IPN: Successfully processed and credited` log

#### 4. **Hash/API Key Verification Failed**
- Check: `CheckoutNow IPN: Invalid API key` logs
- Verify: `CHECKOUTNOW_API_KEY` in `.env` matches CheckoutNow dashboard
- Note: API key verification is optional but recommended

#### 5. **Transaction Already Processed**
- Check: `CheckoutNow IPN: Transaction already processed` logs
- This is normal if webhook is sent multiple times
- System prevents duplicate processing

## 📝 Expected Webhook Payload Format

CheckoutNow sends webhooks in this format:

```json
{
  "event": "payment.approved",
  "transaction_id": "B8WYSFXFQE9Y",
  "status": "approved",
  "amount": "2070.00000000",
  "received_amount": 2070,
  "charges": {
    "percentage": 20.7,
    "fixed": 100,
    "total": 120.7
  },
  "is_mismatch": false,
  "matched_at": "2026-01-23T10:59:41.000000Z",
  "approved_at": "2026-01-23T10:59:41.000000Z",
  "timestamp": "2026-01-23T10:59:41.000000Z"
}
```

## ✅ Success Indicators

A successful webhook processing will show these logs in order:

1. ✅ `CheckoutNow IPN received` - Webhook received
2. ✅ `CheckoutNow IPN payload structure` - Payload parsed
3. ✅ `CheckoutNow IPN: Deposit found` - Deposit located
4. ✅ `CheckoutNow IPN: Processing successful transaction` - Processing started
5. ✅ `CheckoutNow IPN: User balance credited` - Balance updated
6. ✅ `CheckoutNow IPN: Sending webhooks` - Webhooks being sent
7. ✅ `CheckoutNow IPN: Successfully processed and credited` - Complete
8. ✅ `CheckoutNow IPN: Transaction committed successfully` - Database committed

## 🚨 Error Indicators

Watch for these error logs:

- ❌ `CheckoutNow IPN: No transaction ID found` - Missing transaction ID
- ❌ `CheckoutNow IPN: Deposit not found` - Transaction not in database
- ❌ `CheckoutNow IPN: Invalid API key` - API key mismatch
- ❌ `CheckoutNow IPN: Database error` - Database transaction failed
- ❌ `CheckoutNow IPN: User not found` - User doesn't exist

## 🔧 Testing the Webhook

You can test the webhook endpoint using curl:

```bash
curl -X POST https://fadded.net/ipn/checkoutnow \
  -H "Content-Type: application/json" \
  -H "X-API-Key: YOUR_API_KEY" \
  -d '{
    "event": "payment.approved",
    "transaction_id": "TEST123",
    "status": "approved",
    "amount": "1000.00"
  }'
```

**Note:** Replace `TEST123` with an actual transaction ID from your database for testing.

## 📞 Support

If you encounter issues:

1. Check the logs using the commands above
2. Verify the webhook URL in CheckoutNow dashboard
3. Ensure `CHECKOUTNOW_API_KEY` is set correctly in `.env`
4. Check that the transaction exists in the `deposits` table with matching `trx` field
