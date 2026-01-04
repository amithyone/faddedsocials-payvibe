<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Gateway\PayVibe\ProcessController as PayVibeController;




Route::any('e-fund',  'User\UserController@e_fund')->name('e-fund');
Route::any('e-check',  'User\UserController@e_check')->name('e-check');

// PayVibe Payment Gateway Routes
Route::post('/ipn/payvibe', [PayVibeController::class, 'ipn']);
Route::get('/ipn/payvibe/requery/{reference}', [PayVibeController::class, 'checkTransaction']);

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




