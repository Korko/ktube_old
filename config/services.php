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
        'client_id' => env('YOUTUBE_CLIENT_ID'),
        'client_secret' => env('YOUTUBE_CLIENT_SECRET'),
        'redirect' => 'https://ktube.yt/auth/login/callback/google',
        'scopes' => [
            // TODO: Only use "https://www.googleapis.com/auth/youtube.readonly" by default
            // and switch to "https://www.googleapis.com/auth/youtube.force-ssl" for holidays mode
            'https://www.googleapis.com/auth/youtube.force-ssl'
        ]
    ],

];
