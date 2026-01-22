<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'payvibe' => [
        'base_url' => env('PAYVIBE_BASE_URL', 'https://payvibeapi.six3tech.com/api'),
        'public_key' => env('PAYVIBE_PUBLIC_KEY'),
        'secret_key' => env('PAYVIBE_SECRET_KEY'),
        'product_identifier' => env('PAYVIBE_PRODUCT_IDENTIFIER', 'socails'),
    ],

    'checkoutnow' => [
        'base_url' => env('CHECKOUTNOW_BASE_URL', 'https://check-outpay.com/api/v1'),
        'api_key' => env('CHECKOUTNOW_API_KEY'),
        'webhook_url' => env('CHECKOUTNOW_WEBHOOK_URL', env('APP_URL') . '/ipn/checkoutnow'),
    ],

];
