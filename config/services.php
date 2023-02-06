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

    'auth' => [
        'uri' => env('MS_AUTH_API_URL'),
    ],

    'ms' => [
        'subscription' => env('MS_PRODUCTS_SUBSCRIPTION'),
    ],

    'users' => [
        'uri' => env('MS_USERS_API_URL'),
    ],

    'product' => [
        'uri' => env('MS_PRODUCT_API_URL'),
        'price' => [
            'uri' => env('MS_PRODUCT_PRICE_API_URL'),
        ],
        'availability' => env('MS_PRODUCT_AVAILABILITY')
    ],

    'orders' => [
        'uri' => env('MS_ORDERS_API_URL'),
    ],

    'algolia' => [
        'key' => env('ALGOLIA_KEY'),
        'app_id' => env('ALGOLIA_APP_ID'),
        'products_index' => env('ALGOLIA_PRODUCTS_INDEX'),
    ],
];
