# How to Test API Endpoints

## Quick Test Guide

### Step 1: Get Your API Key

First, make sure you have your API key set in `.env`:
```env
SEO_API_KEY=your_api_key_here
```

### Step 2: Test Using cURL (Command Line)

#### Test 1: List Products (Git API)
```bash
curl -H "X-API-Key: your_api_key_here" \
     https://fadded.net/api/git/products/list
```

#### Test 2: List Products with Filters
```bash
curl -H "X-API-Key: your_api_key_here" \
     "https://fadded.net/api/git/products/list?only_in_stock=true&limit=10"
```

#### Test 3: Pull Products
```bash
curl -X POST https://fadded.net/api/git/assets/retrieve \
     -H "X-API-Key: your_api_key_here" \
     -H "Content-Type: application/json" \
     -d '{
       "asset_id": 123,
       "quantity": 5,
       "action": "archive",
       "processed_by": "test_script"
     }'
```

#### Test 4: List Asset Logs
```bash
curl -H "X-API-Key: your_api_key_here" \
     https://fadded.net/api/git/assets/logs
```

#### Test 5: List Deposits (SEO API)
```bash
curl -H "X-API-Key: your_api_key_here" \
     "https://fadded.net/api/seo/analytics/list?limit=10"
```

---

## Test Using Browser (Simple Method)

**Note:** Browser testing only works for GET requests. POST requests need a tool like Postman or cURL.

### For GET Requests:

1. Install a browser extension like "ModHeader" or "Requestly" to add headers
2. Add header: `X-API-Key: your_api_key_here`
3. Visit: `https://fadded.net/api/git/products/list`

Or use this URL format (if your server allows it):
```
https://fadded.net/api/git/products/list?X-API-Key=your_api_key_here
```
*(Note: This is less secure - use header method instead)*

---

## Test Using Postman

1. **Create a new request**
2. **Set method:** GET or POST
3. **Set URL:** `https://fadded.net/api/git/products/list`
4. **Add Header:**
   - Key: `X-API-Key`
   - Value: `your_api_key_here`
5. **For POST requests, add Body:**
   - Select "raw" and "JSON"
   - Add your JSON data
6. **Click Send**

---

## Test Using PHP Script

Create a file `test_api.php`:

```php
<?php

$apiKey = 'your_api_key_here';
$baseUrl = 'https://fadded.net/api';

// Test 1: List Products
$ch = curl_init($baseUrl . '/git/products/list');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'X-API-Key: ' . $apiKey
]);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: " . $httpCode . "\n";
echo "Response: " . $response . "\n";

// Test 2: Pull Products
$data = [
    'asset_id' => 123,
    'quantity' => 5,
    'action' => 'archive',
    'processed_by' => 'php_test'
];
$ch = curl_init($baseUrl . '/git/assets/retrieve');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'X-API-Key: ' . $apiKey,
    'Content-Type: application/json'
]);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "\nHTTP Code: " . $httpCode . "\n";
echo "Response: " . $response . "\n";
```

Run it:
```bash
php test_api.php
```

---

## Test Using JavaScript (Browser Console)

Open browser console (F12) and run:

```javascript
fetch('https://fadded.net/api/git/products/list', {
    headers: {
        'X-API-Key': 'your_api_key_here'
    }
})
.then(response => response.json())
.then(data => console.log(data))
.catch(error => console.error('Error:', error));
```

---

## Expected Responses

### Success Response:
```json
{
    "success": true,
    "data": {
        "products": [...],
        "pagination": {...}
    }
}
```

### Error Response (No API Key):
```json
{
    "success": false,
    "message": "Invalid or missing API key"
}
```

### Error Response (404 - Route Not Found):
```
404 Not Found
```
*(This means routes aren't registered - run `php artisan route:clear`)*

---

## Troubleshooting

### Getting 404 Error?
```bash
# On server, run:
php artisan route:clear
php artisan config:clear
composer dump-autoload
```

### Getting "Invalid API key"?
- Check your `.env` file has `SEO_API_KEY=...`
- Make sure you're using the exact same key in the header
- Run `php artisan config:clear` after changing `.env`

### Getting 500 Error?
- Check server logs: `storage/logs/laravel.log`
- Make sure database migration ran: `php artisan migrate`
- Check if controller files exist

---

## Quick Test Checklist

- [ ] API key is set in `.env`
- [ ] Ran `php artisan config:clear`
- [ ] Ran `php artisan route:clear`
- [ ] Database migration completed
- [ ] Testing with correct URL: `https://fadded.net/api/git/products/list`
- [ ] Using header: `X-API-Key: your_key`
- [ ] Getting JSON response (not 404)

