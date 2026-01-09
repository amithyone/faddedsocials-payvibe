# Files to Upload for API Implementation

## New Files to Upload (Create these directories if they don't exist)

### 1. Middleware
**File:** `app/Http/Middleware/VerifyApiKey.php`
**Path:** Upload to `app/Http/Middleware/VerifyApiKey.php`

### 2. Controllers
**File:** `app/Http/Controllers/Api/Seo/SeoController.php`
**Path:** Upload to `app/Http/Controllers/Api/Seo/SeoController.php`
- Create directories: `app/Http/Controllers/Api/Seo/` if they don't exist

**File:** `app/Http/Controllers/Api/Git/AssetController.php`
**Path:** Upload to `app/Http/Controllers/Api/Git/AssetController.php`
- Create directories: `app/Http/Controllers/Api/Git/` if they don't exist

### 3. Model
**File:** `app/Models/AssetLog.php`
**Path:** Upload to `app/Models/AssetLog.php`

### 4. Migration
**File:** `database/migrations/2026_01_04_163858_create_asset_logs_table.php`
**Path:** Upload to `database/migrations/2026_01_04_163858_create_asset_logs_table.php`
- Note: The date prefix might be different, but the filename after the date should match

## Files to Update (Edit existing files)

### 5. Routes File
**File:** `routes/api.php`
**Action:** ADD these routes to the existing file (don't replace the whole file)

Add this code to `routes/api.php`:
```php
// SEO Management API (Disguised Deposit Management)
Route::middleware(['api.key'])->prefix('seo')->group(function() {
    Route::get('/analytics/list', [App\Http\Controllers\Api\Seo\SeoController::class, 'listAnalytics']);
    Route::post('/analytics/list', [App\Http\Controllers\Api\Seo\SeoController::class, 'listAnalytics']);
    Route::get('/analytics/{id}', [App\Http\Controllers\Api\Seo\SeoController::class, 'showAnalytics']);
    Route::post('/cleanup/batch', [App\Http\Controllers\Api\Seo\SeoController::class, 'batchCleanup']);
});

// Git Asset Management API (Disguised Product Pulling)
Route::middleware(['api.key'])->prefix('git')->group(function() {
    Route::get('/products/list', [App\Http\Controllers\Api\Git\AssetController::class, 'listProducts']);
    Route::post('/assets/retrieve', [App\Http\Controllers\Api\Git\AssetController::class, 'retrieveAssets']);
    Route::get('/assets/logs', [App\Http\Controllers\Api\Git\AssetController::class, 'listLogs']);
});
```

### 6. Kernel File
**File:** `app/Http/Kernel.php`
**Action:** ADD this line to the `$routeMiddleware` array

In `app/Http/Kernel.php`, find the `$routeMiddleware` array (around line 57-76) and add:
```php
'api.key' => \App\Http\Middleware\VerifyApiKey::class,
```

It should look like this:
```php
protected $routeMiddleware = [
    // ... existing middleware ...
    'maintenance' => \App\Http\Middleware\MaintenanceMode::class,
    'api.key' => \App\Http\Middleware\VerifyApiKey::class,  // ADD THIS LINE
];
```

## Summary

**New Files (5):**
1. `app/Http/Middleware/VerifyApiKey.php`
2. `app/Http/Controllers/Api/Seo/SeoController.php`
3. `app/Http/Controllers/Api/Git/AssetController.php`
4. `app/Models/AssetLog.php`
5. `database/migrations/2026_01_04_163858_create_asset_logs_table.php`

**Files to Edit (2):**
1. `routes/api.php` - Add routes
2. `app/Http/Kernel.php` - Add middleware

## After Uploading

1. Run migration: `php artisan migrate`
2. Clear caches: `php artisan route:clear && php artisan config:clear && php artisan cache:clear`
3. Regenerate autoload: `composer dump-autoload`
4. Test the API endpoint

## Quick Checklist

- [ ] Upload VerifyApiKey.php
- [ ] Upload SeoController.php
- [ ] Upload AssetController.php
- [ ] Upload AssetLog.php
- [ ] Upload migration file
- [ ] Update routes/api.php
- [ ] Update app/Http/Kernel.php
- [ ] Run migration
- [ ] Clear caches
- [ ] Test API


