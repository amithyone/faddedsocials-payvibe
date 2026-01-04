# Route Not Found - Troubleshooting Guide

If you're getting "The route api/git/products/list could not be found", follow these steps:

## Step 1: Make Sure Files Are Uploaded

Verify these files exist on your server:
- `routes/api.php` (should contain the git routes)
- `app/Http/Controllers/Api/Git/AssetController.php`
- `app/Http/Middleware/VerifyApiKey.php`
- `app/Http/Kernel.php` (should have 'api.key' middleware registered)

## Step 2: Navigate to Laravel Root Directory

Find where your `artisan` file is:
```bash
find ~ -name "artisan" -type f 2>/dev/null | head -1
```

Then navigate there:
```bash
cd /path/to/laravel/root
```

## Step 3: Run These Commands (In Order)

```bash
# Clear all caches
php artisan route:clear
php artisan config:clear
php artisan cache:clear

# Regenerate autoload files (important!)
composer dump-autoload

# If routes are cached, clear it
php artisan route:clear
```

## Step 4: Verify the Route Exists

Check if the route is registered (this might error, but that's okay):
```bash
php artisan route:list | grep git
```

Or check the routes file directly:
```bash
cat routes/api.php | grep -A 5 "git"
```

You should see:
```php
Route::middleware(['api.key'])->prefix('git')->group(function() {
    Route::get('/products/list', [App\Http\Controllers\Api\Git\AssetController::class, 'listProducts']);
    ...
});
```

## Step 5: Test the Route

Try accessing it directly:
```bash
curl -H "X-API-Key: your_api_key" https://fadded.net/api/git/products/list
```

## Common Issues:

1. **Files not uploaded**: Make sure you pulled/pushed the latest code
2. **Autoload not updated**: Run `composer dump-autoload`
3. **Route cache**: Run `php artisan route:clear`
4. **Wrong URL**: Make sure you're using `https://fadded.net/api/git/products/list` (not `/api/api/git/...`)

## Quick Fix - Run All At Once:

```bash
cd /path/to/laravel/root
composer dump-autoload
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

Then test again!

