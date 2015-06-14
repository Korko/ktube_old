<?php

namespace Korko\kTube\Libs;

use Korko\kTube\Token;
use Exception;

class Youtube
{
    public static function getAuthUrl()
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

    protected static function callApi($url, $params = [])
    {
        $s = curl_init($url);

        if ($params !== []) {
            curl_setopt($s, CURLOPT_POST, true);
            curl_setopt($s, CURLOPT_POSTFIELDS, $params);
        }

        curl_setopt($s, CURLOPT_SSL_VERIFYPEER, 2);
        curl_setopt($s, CURLOPT_CAINFO, base_path().'/resources/cacert.pem');
        curl_setopt($s, CURLOPT_RETURNTRANSFER, true);

        $data = json_decode(curl_exec($s));

        if (curl_errno($s) || is_null($data)) {
            throw new Exception('Curl error');
        }
        if (isset($data->error)) {
            throw new Exception($data->error->message);
        }

        return $data;
    }

    public static function validateCode($code)
    {
        return self::callApi('https://accounts.google.com/o/oauth2/token', [
            'code' => $code,
            'client_id' => config('youtube.client_id'),
            'client_secret' => config('youtube.client_secret'),
            'redirect_uri' => url('oauth/youtube/auth'),
            'grant_type' => 'authorization_code'
        ]);
    }

    public static function refreshToken(Token $token)
    {
        return self::callApi('https://accounts.google.com/o/oauth2/token', [
            'client_id' => config('youtube.client_id'),
            'client_secret' => config('youtube.client_secret'),
            'refresh_token' => $token->refresh_token,
            'grant_type' => 'refresh_token'
        ]);
    }

    public static function getActivities(Token $token)
    {
        $channelIds = implode(',', self::getSubscriptions($token));
        return self::callApi('https://www.googleapis.com/youtube/v3/channels?'.http_build_query([
            'part' => 'snippet',
            'id' => $channelIds,
            'access_token' => $token->access_token
        ]));
    }

    public static function getSubscriptions(Token $token)
    {
        $pageToken = null;
        $subscriptions = [];
        do {
            $data = self::callApi('https://www.googleapis.com/youtube/v3/subscriptions?'.http_build_query([
                'part' => 'id',
                'order' => 'unread',
                'pageToken' => $pageToken,
                'access_token' => $token->access_token,
                'mine' => 'true'
            ]));
            foreach ($data->items as $item) {
                $subscriptions[] = $item->id;
            }
            $pageToken = isset($data->nextPageToken) ? $data->nextPageToken : null;
        } while ($pageToken !== null);
        return $subscriptions;
    }
}
