<?php

namespace Korko\kTube\Libs;

class Youtube
{
    public static function  getAuthUrl()
    {
        return 'https://accounts.google.com/o/oauth2/auth?'.http_build_query([
            'client_id' => config('youtube.client_id'),
            'redirect_uri' => url('oauth/youtube/auth'),
            'response_type' => 'code',
            'scope' => 'https://www.googleapis.com/auth/youtube.force-ssl',
            'approval_prompt' => 'force',
            'access_type' => 'offline',
            'state' => null,
            'login_hint' => null
        ]);
    }

    public static function validateCode($code)
    {
        $s = curl_init('https://accounts.google.com/o/oauth2/token');
        curl_setopt($s, CURLOPT_POST, true);
        curl_setopt($s, CURLOPT_POSTFIELDS, [
            'code' => $code,
            'client_id' => config('youtube.client_id'),
            'client_secret' => config('youtube.client_secret'),
            'redirect_uri' => url('oauth/youtube/auth'),
            'grant_type' => 'authorization_code'
        ]);
        curl_setopt($s, CURLOPT_SSL_VERIFYPEER, 2);
        curl_setopt($s, CURLOPT_CAINFO, base_path().'/resources/cacert.pem');
        curl_setopt($s, CURLOPT_RETURNTRANSFER, true);
        $data = json_decode(curl_exec($s));

        if (curl_errno($s) || is_null($data) || isset($data->error)) {
            throw new Exception('Invalid Code');
        }

        return $data;
    }
]
