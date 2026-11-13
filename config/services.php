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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],
    
'finnhub' => [
    'keys' => array_values(array_filter(array_map(
        'trim',
        explode(',', env('FINNHUB_API_KEYS', env('FINNHUB_API_KEY', '')))
    ))),
],

'twelvedata' => [
    'keys' => array_values(array_filter(array_map(
        'trim',
        explode(',', env('TWELVE_DATA_API_KEYS', env('TWELVE_DATA_API_KEY', '')))
    ))),
],

'alphavantage' => [
    'keys' => array_values(array_filter(array_map(
        'trim',
        explode(',', env('ALPHA_VANTAGE_API_KEYS', env('ALPHA_VANTAGE_API_KEY', '')))
    ))),
],


    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'tinymce' => [
        'key' => env('TINYMCE_API_KEY'),
    ],

];
