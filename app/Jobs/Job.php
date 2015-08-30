<?php

namespace Korko\kTube\Jobs;

use Google_Client;
use Google_Service_YouTube;
use Illuminate\Bus\Queueable;
use Korko\kTube\Account;

abstract class Job
{
    /*
    |--------------------------------------------------------------------------
    | Queueable Jobs
    |--------------------------------------------------------------------------
    |
    | This job base class provides a central location to place any logic that
    | is shared across all of your jobs. The trait included with the class
    | provides access to the "queueOn" and "delay" queue helper methods.
    |
    */

    use Queueable;

    /**
     * Get a fresh Youtube API object
     * @param  Account|null            $account
     * @return Google_Service_YouTube
     */
    protected function getYoutubeApi(Account $account = null)
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
