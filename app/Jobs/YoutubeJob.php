<?php

namespace Korko\kTube\Jobs;

use Google_Client;
use Google_Service_YouTube;
use Korko\kTube\Account;

trait YoutubeJob
{
    /**
     * Get a fresh Youtube API object
     * @param  Account|null            $account
     * @return Google_Service_YouTube
     */
    protected function getApi(Account $account = null)
    {
        $pageToken = null;

        $client = new Google_Client();

        $client->setDeveloperKey(config('services.youtube.api_key'));

        if (isset($account)) {
            $client->setAccessToken(json_encode([
                'access_token' => $account->access_token,
                'created' => time(),
                'expires_in' => 3600
            ]));
        }

        return new Google_Service_YouTube($client);
    }
}
