# New Server Setup - Fix Invalid Credentials Error

## Error
```
Class "UserController" does not exist
ReflectionException: Class "UserController" does not exist
```

## Cause
After moving to a new server, the Composer autoloader needs to be regenerated. Also, some routes use old string-based syntax that can cause issues.

## Solution

### Step 1: Navigate to Laravel Root
```bash
cd /home/wolrdhome/public_html/core
```

### Step 2: Regenerate Composer Autoloader
```bash
composer dump-autoload
```

### Step 3: Clear All Caches
```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Step 4: Verify Routes
```bash
php artisan route:list | grep "e-fund\|e-check"
```

You should see the routes listed.

### Step 5: Check File Permissions (if still having issues)
```bash
# Ensure proper permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## What Was Fixed

Changed old string-based route syntax to modern class-based syntax:

**Before:**
```php
Route::any('e-fund',  'User\UserController@e_fund')->name('e-fund');
```

**After:**
```php
Route::any('e-fund',  [UserController::class, 'e_fund'])->name('e-fund');
```

This ensures Laravel can properly find and autoload the controller class.

## Additional Checks

### 1. Verify UserController Exists
```bash
ls -la app/Http/Controllers/User/UserController.php
```

Should show the file exists.

### 2. Check Namespace
The file should have:
```php
namespace App\Http\Controllers\User;
```

### 3. Verify Composer Autoload
```bash
composer dump-autoload -v
```

Look for any errors or warnings.

## If Still Not Working

### Check .env File
```bash
# Make sure APP_ENV is set correctly
grep APP_ENV .env
```

### Check RouteServiceProvider
The namespace should be set in `app/Providers/RouteServiceProvider.php`:
```php
protected $namespace = 'App\Http\Controllers';
```

### Test Route Directly
```bash
php artisan tinker
>>> Route::getRoutes()->getByName('e-fund');
```

Should return route information.

## Quick Fix Script

Run this complete fix script:

```bash
cd /home/wolrdhome/public_html/core
composer dump-autoload
php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan optimize:clear
```

Then test your routes again.

