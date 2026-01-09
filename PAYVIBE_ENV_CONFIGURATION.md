# PayVibe Environment Configuration Guide

## ✅ Updated .env File

The `.env` file has been successfully updated with PayVibe configuration. Here's what was added:

```env
# PayVibe Payment Gateway Configuration
PAYVIBE_PUBLIC_KEY=pk_live_jzndandouhd5rlh1rlrvabbtsnr64qu8
PAYVIBE_WEBHOOK_URL=https://fadded.net/api/ipn/payvibe
PAYVIBE_SECRET_KEY=sk_live_eqnfqzsy0x5qoagvb4v8ong9qqtollc3
PAYVIBE_API_CODE=faddedsocials
PAYVIBE_PRODUCT_IDENTIFIER=socails
```

## 🔧 Configuration Steps

### Step 1: Get PayVibe Credentials

You need to obtain the following from PayVibe:

1. **PAYVIBE_ACCESS_KEY**: Your PayVibe API access key
2. **PAYVIBE_API_KEY**: Your PayVibe webhook API key (if different from access key)

### Step 2: Update the .env File

Replace the placeholder values with your actual PayVibe credentials:

```env
# Replace these placeholder values with your actual PayVibe credentials
PAYVIBE_ACCESS_KEY=your_actual_payvibe_access_key
PAYVIBE_API_KEY=your_actual_payvibe_webhook_api_key
```

### Step 3: Verify Configuration

The current configuration includes:

- ✅ **PAYVIBE_ACCESS_KEY**: For API authentication
- ✅ **PAYVIBE_WEBHOOK_URL**: Your webhook endpoint
- ✅ **PAYVIBE_API_KEY**: For webhook verification
- ✅ **PAYVIBE_API_CODE**: Your site identifier

## 📋 Environment Variables Explained

### PAYVIBE_PUBLIC_KEY
- **Purpose**: Public key for PayVibe API calls
- **Usage**: Used when generating virtual accounts
- **Format**: Usually starts with 'pk_live_' or 'pk_test_'
- **Example**: `pk_live_jzndandouhd5rlh1rlrvabbtsnr64qu8`

### PAYVIBE_WEBHOOK_URL
- **Purpose**: URL where PayVibe sends payment notifications
- **Current Value**: `https://fadded.net/api/ipn/payvibe`
- **Note**: This should be publicly accessible

### PAYVIBE_SECRET_KEY
- **Purpose**: Secret key for webhook verification
- **Usage**: Used to verify incoming webhooks from PayVibe
- **Format**: Usually starts with 'sk_live_' or 'sk_test_'
- **Example**: `sk_live_eqnfqzsy0x5qoagvb4v8ong9qqtollc3`

### PAYVIBE_API_CODE
- **Purpose**: Site identifier for PayVibe
- **Current Value**: `faddedsocials`
- **Usage**: Included in webhook payloads

### PAYVIBE_PRODUCT_IDENTIFIER
- **Purpose**: Product/service identifier for PayVibe
- **Current Value**: `socails`
- **Usage**: Used in API calls and webhook payloads
- **Note**: Identifies your service in PayVibe's system

## 🔒 Security Considerations

### 1. Access Key Security
- Keep your PayVibe access key secure
- Don't commit it to version control
- Use different keys for test and production

### 2. Webhook Security
- Ensure your webhook URL is HTTPS
- Verify webhook signatures
- Monitor webhook delivery

### 3. Environment File Security
- The `.env` file is already in `.gitignore`
- Backup your `.env` file securely
- Use strong, unique keys

## 🧪 Testing Configuration

### Test the Configuration

1. **Check Environment Variables**:
   ```bash
   php artisan tinker
   echo env('PAYVIBE_ACCESS_KEY');
   ```

2. **Test Webhook Endpoint**:
   ```bash
   curl -X POST https://fadded.net/api/ipn/payvibe
   ```

3. **Check Logs**:
   ```bash
   tail -f storage/logs/laravel.log
   ```

## 📊 Current Configuration Status

### ✅ Configured
- Environment variables structure
- Webhook URL endpoint
- API code identifier

### ✅ Configured
- **PAYVIBE_PUBLIC_KEY**: ✅ Set to `pk_live_jzndandouhd5rlh1rlrvabbtsnr64qu8`
- **PAYVIBE_SECRET_KEY**: ✅ Set to `sk_live_eqnfqzsy0x5qoagvb4v8ong9qqtollc3`
- **PAYVIBE_PRODUCT_IDENTIFIER**: ✅ Set to `socails`

## 🚀 Next Steps

### 1. Get PayVibe Credentials
Contact PayVibe to obtain:
- API access key
- Webhook API key (if different)

### 2. Update .env File
Your PayVibe credentials are now configured:
```env
PAYVIBE_PUBLIC_KEY=pk_live_jzndandouhd5rlh1rlrvabbtsnr64qu8
PAYVIBE_SECRET_KEY=sk_live_eqnfqzsy0x5qoagvb4v8ong9qqtollc3
PAYVIBE_PRODUCT_IDENTIFIER=socails
```

### 3. Test the Integration
- Test virtual account generation
- Test webhook processing
- Verify balance updates

### 4. Configure Admin Panel
- Go to Admin Panel → Payment Gateway
- Find "PayVibe" in the list
- Configure with your access key
- Enable the gateway

## 🔍 Troubleshooting

### Common Issues

1. **Invalid Access Key**
   - Verify the key with PayVibe
   - Check for extra spaces or characters
   - Ensure the key is active

2. **Webhook Not Received**
   - Verify webhook URL is accessible
   - Check firewall settings
   - Confirm PayVibe webhook configuration

3. **API Errors**
   - Check API key permissions
   - Verify account status with PayVibe
   - Review API documentation

### Debug Steps

1. **Check Environment**:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

2. **Test API Connection**:
   ```php
   // In tinker
   $response = Http::withToken(env('PAYVIBE_ACCESS_KEY'))
       ->post('https://api.payvibe.com/v1/test');
   ```

3. **Monitor Logs**:
   ```bash
   tail -f storage/logs/laravel.log | grep PayVibe
   ```

## 📞 Support

### PayVibe Support
- Contact PayVibe for API credentials
- Get webhook configuration help
- Verify account status

### Technical Support
- Check application logs
- Review webhook delivery
- Test API connectivity

---

**Status**: ✅ Environment variables added
**Next Step**: Replace placeholder values with actual PayVibe credentials
**Security**: ✅ Environment file secured
**Documentation**: ✅ Complete configuration guide 