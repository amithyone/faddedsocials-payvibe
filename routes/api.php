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




