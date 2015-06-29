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

    'youtube' => [
        'client_id' => env('YOUTUBE_KEY'),
        'client_secret' => env('YOUTUBE_SECRET'),
        'redirect' => env('YOUTUBE_REDIRECT_URI'),
        'scopes' => [
            // TODO: Only use "https://www.googleapis.com/auth/youtube.readonly" by default
            // and switch to "https://www.googleapis.com/auth/youtube.force-ssl" for holidays mode
            'https://www.googleapis.com/auth/youtube.force-ssl'
        ]
    ],

    'vimeo' => [
        'client_id' => env('VIMEO_KEY'),
        'client_secret' => env('VIMEO_SECRET'),
        'redirect' => env('VIMEO_REDIRECT_URI')
    ],

    'dailymotion' => [
        'client_id' => env('DAILYMOTION_KEY'),
        'client_secret' => env('DAILYMOTION_SECRET'),
        'redirect' => env('DAILYMOTION_REDIRECT_URI')
    ]
];
