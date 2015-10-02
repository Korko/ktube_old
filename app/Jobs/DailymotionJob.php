<?php

namespace Korko\kTube\Jobs;

use Dailymotion;
use Korko\kTube\Account;

trait DailymotionJob
{
    /**
     * Get a fresh Dailymotion API object
     * @param  Account|null            $account
     * @return Dailymotion
     */
    protected function getApi(Account $account = null)
    {
        $pageToken = null;

        $client = new Dailymotion();

	$grantType = isset($account) ?
            Dailymotion::GRANT_TYPE_AUTHORIZATION :
            Dailymotion::GRANT_TYPE_CLIENT_CREDENTIALS;

        $client->setGrantType(
            $grantType,
            config('services.dailymotion.client_id'),
            config('services.dailymotion.client_secret')
        );

        if (isset($account)) {
            $client->setSession([
                "access_token"  => $account->access_token,
                "token_type"    => "Bearer",
                "expires_in"    => 36000,
                "refresh_token" => $account->refresh_token,
                "scope"         => "",
                "uid"           => $account->account_id,
                "grant_type"    => $grantType
            ]);
        }

        return $client;
    }
}
