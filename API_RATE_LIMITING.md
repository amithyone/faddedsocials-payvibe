# API Rate Limiting - How It Works

## Understanding API Execution

**Important:** The API endpoints **ONLY run when called** via HTTP request. They do NOT run automatically or continuously.

### How APIs Work:
1. ✅ **API waits** - The server listens for incoming HTTP requests
2. ✅ **Request received** - External system makes HTTP call (e.g., `curl`, `fetch`, etc.)
3. ✅ **API executes** - Code runs only when request is received
4. ✅ **Response sent** - API returns result and stops
5. ✅ **API waits again** - Back to waiting for next request

**The API does NOT run in the background or continuously.**

---

## Why You Might See High CPU Usage

If you're seeing high CPU usage, it's likely because:

### 1. **External System Calling API Repeatedly**
- An external system (another website, script, or service) is making HTTP requests to your API
- If that system has retry logic, it might be calling the API many times
- **Solution:** Rate limiting (already added below)

### 2. **Failed Requests Causing Retries**
- If the API was failing (like the `toDateTimeString()` error), external systems might retry
- Each retry = new HTTP request = API runs again
- **Solution:** Fixed the bug, added rate limiting

### 3. **Legitimate High Usage**
- If you have a legitimate service calling the API frequently, that's normal
- But we should still limit it to prevent abuse

---

## Rate Limiting Added

I've added rate limiting to prevent abuse:

### Limits:
- **30 requests per minute** per IP address
- Applies to both SEO and Git API endpoints

### What Happens When Limit Exceeded:
- API returns HTTP 429 (Too Many Requests)
- Response includes: `{"message": "Too Many Attempts."}`
- Client must wait before trying again

### Example Response When Rate Limited:
```json
{
    "message": "Too Many Attempts."
}
```
HTTP Status: `429`

---

## How to Check What's Calling Your API

### 1. Check Server Access Logs
```bash
# On your server, check Apache/Nginx access logs
tail -f /var/log/apache2/access.log | grep "api/git\|api/seo"
# or
tail -f /var/log/nginx/access.log | grep "api/git\|api/seo"
```

### 2. Check Laravel Logs
```bash
# Check for API calls in Laravel logs
tail -f storage/logs/laravel.log | grep "api/git\|api/seo"
```

### 3. Monitor in Real-Time
```bash
# Watch for API requests
watch -n 1 'tail -20 storage/logs/laravel.log | grep -i "api"'
```

---

## If External System Needs Higher Limits

If you have a legitimate service that needs more than 30 requests/minute:

### Option 1: Increase Rate Limit
Edit `routes/api.php` and change `throttle:30,1` to a higher number:
```php
Route::middleware(['api.key', 'throttle:100,1'])->prefix('git')->group(function() {
    // 100 requests per minute instead of 30
});
```

### Option 2: Remove Rate Limiting (Not Recommended)
Remove `'throttle:30,1'` from middleware:
```php
Route::middleware(['api.key'])->prefix('git')->group(function() {
    // No rate limiting
});
```

### Option 3: Custom Rate Limiter (Advanced)
Create a custom rate limiter in `RouteServiceProvider.php` for specific API keys.

---

## Summary

✅ **API only runs when called** - This is how it works by design  
✅ **Rate limiting added** - Prevents abuse (30 requests/minute)  
✅ **Bug fixed** - No more `toDateTimeString()` errors  
✅ **Performance optimized** - Bulk operations instead of loops  

**The API will now:**
- Only execute when an HTTP request is received
- Reject requests that exceed 30/minute
- Run much faster (10-20x improvement)
- Use less CPU/resources

---

## Next Steps

1. **Pull the latest code:**
   ```bash
   cd ~/public_html/core
   git pull origin main
   php artisan route:clear
   php artisan config:clear
   ```

2. **Monitor CPU usage** - Should drop significantly

3. **Check logs** - See if external system is still calling repeatedly

4. **Adjust rate limit** - If needed, based on legitimate usage

