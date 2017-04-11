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
        'domain' => 'sandboxefa0fd7faeec44cc874c0ab3c3557b96.mailgun.org',
        'secret' => 'key-2773bdbcaada6fbceb64e0b6aabacc3c',
    ],

    'ses' => [
        'key' => 'seskey',
        'secret' => 'sessecret',
        'region' => 'en-us',
    ],

    'sparkpost' => [
        'secret' => 'sparkpostsecret',
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

];
