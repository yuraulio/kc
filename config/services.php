<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
        // optional guzzle specific configuration
        'guzzle' => [
            'verify' => true,
            'decode_content' => true,
        ],
        'options' => [
            // configure endpoint, if not default
            'endpoint' => env('SPARKPOST_ENDPOINT'),

            // optional Sparkpost API options go here
            'return_path' => 'mail@bounces.domain.com',
            'options' => [
                'open_tracking' => false,
                'click_tracking' => false,
                'transactional' => true,
            ],
        ],
    ],

    'stripe' => [
        'model' => App\Model\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'instagram' => [
        'profile' => env('instagram_profile'),
        'business_key' => env('instagram_business_key'),
        'facebook_access_token' => env('facebook_access_token'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT_URI'),
    ],

    'vimeo' => [
        'token' => env('vimeo_token'),
        'password' => env('video_password'),
        'id' => env('client_id'),
        'secret' => env('client_secret'),
    ],

    'promotions' => [
        'BLACKFRIDAY' => env('BLACKFRIDAY'),
        'CYBERMONDAY' => env('CYBERMONDAY'),
    ],

    'sms' => [
        'service_id' => env('service_id'),
        'secret_key' => env('secret_key'),
        'sender_id' => env('sender_id'),
        'token' => env('token'),
    ],
];
