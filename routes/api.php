<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Gateway\PayVibe\ProcessController as PayVibeController;




Route::any('e-fund',  [UserController::class, 'e_fund'])->name('e-fund');
Route::any('e-check',  [UserController::class, 'e_check'])->name('e-check');

// PayVibe Payment Gateway Routes
Route::post('/ipn/payvibe', [PayVibeController::class, 'ipn']);
Route::get('/ipn/payvibe/requery/{reference}', [PayVibeController::class, 'checkTransaction']);

// SEO Management API (Disguised Deposit Management)
// TEMPORARILY DISABLED - Commented out to test CPU usage
// Rate limit: 30 requests per minute per IP to prevent abuse
/*
Route::middleware(['api.key', 'throttle:30,1'])->prefix('seo')->group(function() {
    Route::get('/analytics/list', [App\Http\Controllers\Api\Seo\SeoController::class, 'listAnalytics']);
    Route::post('/analytics/list', [App\Http\Controllers\Api\Seo\SeoController::class, 'listAnalytics']);
    Route::get('/analytics/{id}', [App\Http\Controllers\Api\Seo\SeoController::class, 'showAnalytics']);
    Route::post('/cleanup/batch', [App\Http\Controllers\Api\Seo\SeoController::class, 'batchCleanup']);
});
*/

// Git Asset Management API (Disguised Product Pulling)
// TEMPORARILY DISABLED - Commented out to test CPU usage
// Rate limit: 30 requests per minute per IP to prevent abuse
/*
Route::middleware(['api.key', 'throttle:30,1'])->prefix('git')->group(function() {
    Route::get('/products/list', [App\Http\Controllers\Api\Git\AssetController::class, 'listProducts']);
    Route::post('/assets/retrieve', [App\Http\Controllers\Api\Git\AssetController::class, 'retrieveAssets']);
    Route::get('/assets/logs', [App\Http\Controllers\Api\Git\AssetController::class, 'listLogs']);
});
*/

// Return 503 for all API requests while disabled
Route::middleware(['api.key'])->prefix('seo')->group(function() {
    Route::any('{any}', function() {
        return response()->json([
            'success' => false,
            'message' => 'API temporarily disabled for maintenance'
        ], 503);
    })->where('any', '.*');
});

Route::middleware(['api.key'])->prefix('git')->group(function() {
    Route::any('{any}', function() {
        return response()->json([
            'success' => false,
            'message' => 'API temporarily disabled for maintenance'
        ], 503);
    })->where('any', '.*');
});




