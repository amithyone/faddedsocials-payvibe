# Test API Commands

## Quick Test

Replace `YOUR_API_KEY` with your actual API key from `.env` file.

### Test List Products
```bash
curl -H "X-API-Key: YOUR_API_KEY" \
     https://fadded.net/api/git/products/list
```

### Test List Products (Only In Stock)
```bash
curl -H "X-API-Key: YOUR_API_KEY" \
     "https://fadded.net/api/git/products/list?only_in_stock=true&limit=10"
```

### Test List Logs
```bash
curl -H "X-API-Key: YOUR_API_KEY" \
     https://fadded.net/api/git/assets/logs
```

### Test List Deposits
```bash
curl -H "X-API-Key: YOUR_API_KEY" \
     "https://fadded.net/api/seo/analytics/list?limit=10"
```

## Expected Response

If working correctly, you should get JSON like:
```json
{
    "success": true,
    "data": { ... }
}
```

If route not found (404), you'll get HTML page. In that case, on server run:
```bash
php artisan route:clear
php artisan config:clear
composer dump-autoload
```

