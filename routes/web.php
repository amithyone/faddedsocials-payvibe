<?php

use App\Http\Controllers\ProxyController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

Route::get('/test-mail', function () {
    Mail::raw('Test email from Fadded.net', function ($message) {
        $message->to('amithyone@gmail.com')
                ->subject('Fadded Test Mail');
    });

    return 'Mail sent!';
});



Route::get('/clear', function(){
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});

Route::get('/proxy', [ProxyController::class, 'proxy']);


Route::get('verify', 'Gateway\Enkpay\ProcessController@ipn')->name('enkpay');


Route::any('change-password', [SiteController::class, 'chnage_password']);

Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');

// User Support Ticket
Route::controller('TicketController')->prefix('ticket')->name('ticket.')->group(function () {
    Route::get('/', 'supportTicket')->name('index');
    Route::get('new', 'openSupportTicket')->name('open');
    Route::post('create', 'storeSupportTicket')->name('store');
    Route::get('view/{ticket}', 'viewTicket')->name('view');
    Route::post('reply/{ticket}', 'replyTicket')->name('reply');
    Route::post('close/{ticket}', 'closeTicket')->name('close');
    Route::get('download/{ticket}', 'ticketDownload')->name('download');
});

Route::controller('SiteController')->group(function () {
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactSubmit');
    Route::any('/search', 'search');

    Route::get('/change/{lang?}', 'changeLanguage')->name('lang');

    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');

    Route::get('/cookie/accept', 'cookieAccept')->name('cookie.accept');

    Route::get('blog', 'blog')->name('blog');
    Route::get('blog/{slug}/{id}', 'blogDetails')->name('blog.details');

    Route::get('policy/{slug}/{id}', 'policyPages')->name('policy.pages');

    Route::get('placeholder-image/{size}', 'placeholderImage')->name('placeholder.image');
    Route::post('/subscribe', 'SiteController@subscribe')->name('subscribe');

    Route::get('/products/{category?}/{id?}', 'products')->name('products');
    Route::get('/category-products/{slug?}/{id?}', 'categoryProducts')->name('category.products');
    Route::get('/product/details/{id}', 'productDetails')->name('product.details');


    Route::any('e-fund',  'e_fund')->name('e-fund');
    Route::any('e-check',  'e_check')->name('e-check');


    Route::get('/{slug}', 'pages')->name('pages');
    Route::get('/', 'index')->name('home');




});
