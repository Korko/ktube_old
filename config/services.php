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

    'google' => [
        'client_id'     => env('YOUTUBE_KEY'),
        'client_secret' => env('YOUTUBE_SECRET'),
        'redirect'      => env('YOUTUBE_REDIRECT_URI'),
    ],

    'vimeo' => [
        'client_id'     => env('VIMEO_KEY'),
        'client_secret' => env('VIMEO_SECRET'),
        'redirect'      => env('VIMEO_REDIRECT_URI'),
    ],

    'dailymotion' => [
        'client_id'     => env('DAILYMOTION_KEY'),
        'client_secret' => env('DAILYMOTION_SECRET'),
        'redirect'      => env('DAILYMOTION_REDIRECT_URI'),
    ],

    'youtube' => [
        'api_key'       => env('YOUTUBE_API_KEY'),
    ],

    'facebook' => [
        'client_id'     => env('FACEBOOK_APP_ID'),
        'client_secret' => env('FACEBOOK_APP_SECRET'),
        'redirect'      => env('FACEBOOK_REDIRECT_URI')
    ],
];
