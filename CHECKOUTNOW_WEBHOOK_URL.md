# CheckoutNow Webhook URL

## 🌐 **Webhook Endpoint**

### **Primary Webhook URL**
```
POST {YOUR_APP_URL}/ipn/checkoutnow
```

### **For Your Site (faddedsocials.com)**
```
POST https://faddedsocials.com/ipn/checkoutnow
```

## 📋 **Route Details**

- **Method:** `POST`
- **Route Path:** `/ipn/checkoutnow`
- **Controller:** `App\Http\Controllers\Gateway\CheckoutNow\ProcessController@ipn`
- **Route Name:** `ipn.CheckoutNow`

## 🔧 **Configuration**

The webhook URL is configured in `config/services.php`:

```php
'checkoutnow' => [
    'base_url' => env('CHECKOUTNOW_BASE_URL', 'https://check-outpay.com/api/v1'),
    'api_key' => env('CHECKOUTNOW_API_KEY'),
    'webhook_url' => env('CHECKOUTNOW_WEBHOOK_URL', env('APP_URL') . '/ipn/checkoutnow'),
],
```

## 📝 **What to Configure in CheckoutNow Dashboard**

When setting up your CheckoutNow account, use this webhook URL:

```
https://faddedsocials.com/ipn/checkoutnow
```

## 🔄 **Requery Endpoint (Optional)**

If you need to manually check transaction status:

```
GET {YOUR_APP_URL}/ipn/checkoutnow/requery/{transactionId}
```

Example:
```
GET https://faddedsocials.com/ipn/checkoutnow/requery/123456
```

## 🔒 **Security Notes**

- The webhook endpoint accepts POST requests
- Make sure your CheckoutNow API key is set in `.env`:
  ```env
  CHECKOUTNOW_API_KEY=your_api_key_here
  CHECKOUTNOW_BASE_URL=https://check-outpay.com/api/v1
  ```

## 📊 **What Happens When Webhook is Received**

1. CheckoutNow sends POST request to `/ipn/checkoutnow`
2. Controller validates the webhook data
3. Transaction status is updated in database
4. User balance is credited if payment successful
5. Webhook notification sent to Xtrabusiness (if configured)

## ✅ **Testing**

You can test the webhook endpoint using curl:

```bash
curl -X POST https://faddedsocials.com/ipn/checkoutnow \
  -H "Content-Type: application/json" \
  -d '{
    "transaction_id": "test123",
    "status": "success",
    "amount": 1000
  }'
```

---

**Note:** Replace `faddedsocials.com` with your actual domain if different.
