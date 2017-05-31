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
        'domain' => 'mailer.wholehousetransmitter.com',
        'secret' => 'key-2773bdbcaada6fbceb64e0b6aabacc3c',
    ],

    'ses' => [
        'key' => 'seskey',
        'secret' => 'sessecret',
        'region' => 'en-us',
    ],

    'sparkpost' => [
        'secret' => 'b25463832f5c1bf495c1e0eaac34bff2b4d53213',
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

];
