<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'mandrill' => [
        'secret' => env('MANDRILL_SECRET'),
    ],

    'ses' => [
        'key'    => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'stripe' => [
        'model'  => App\Models\User::class,
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'github' => [
        'client_id'     => env('CLIENT_ID'),
        'client_secret' => env('CLIENT_SECRET'),
        'redirect'      => str_finish(env('APP_URL'), '/').'auth/callback?driver=github',
    ],

    'baidu_translate' => [
        'appid' => env('BAIDU_TRANSLATE_APPID'),
        'key'   => env('BAIDU_TRANSLATE_KEY'),
    ],

    'bearychat' => [
        'hook' => env('BEARYCHAT_HOOK'),
    ],

    'weixin' => [
        'client_id'     => env('WEIXIN_KEY'),
        'client_secret' => env('WEIXIN_SECRET'),
        'redirect'      => env('WEIXIN_REDIRECT_URI'),
        'auth_base_uri' => 'https://open.weixin.qq.com/connect/qrconnect',
    ],
];
