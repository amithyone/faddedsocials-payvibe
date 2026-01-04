# Quick Fix - Route Not Found Error

## The Problem
Getting "Endpoint not found" for `/api/git/products/list`

## Solution - Run These Commands on Server

**1. Navigate to Laravel root (where `artisan` file is):**
```bash
cd /path/to/laravel/root
```

**2. Run these commands in order:**
```bash
# Clear all caches
php artisan route:clear
php artisan config:clear  
php artisan cache:clear

# Regenerate autoload (IMPORTANT!)
composer dump-autoload

# Clear routes again
php artisan route:clear
```

## Verify Files Exist

Check these files exist on your server:
```bash
ls -la routes/api.php
ls -la app/Http/Controllers/Api/Git/AssetController.php
ls -la app/Http/Middleware/VerifyApiKey.php
```

## Quick Test

Test if routes are loading:
```bash
php artisan route:list | grep git
```

You should see the git routes listed.

## Still Not Working?

1. **Make sure you pulled the latest code from git:**
   ```bash
   git pull origin main
   ```

2. **Check the routes file directly:**
   ```bash
   cat routes/api.php | grep -A 3 "git"
   ```
   
   Should show:
   ```php
   Route::middleware(['api.key'])->prefix('git')->group(function() {
       Route::get('/products/list', ...
   ```

3. **Test with a simple curl (no API key first to see error):**
   ```bash
   curl https://fadded.net/api/git/products/list
   ```
   
   Should return: `{"success":false,"message":"Invalid or missing API key"}`
   
   If you get 404, routes aren't registered.

## Most Common Issue

**Routes are cached!** Always run `php artisan route:clear` after updating routes.

