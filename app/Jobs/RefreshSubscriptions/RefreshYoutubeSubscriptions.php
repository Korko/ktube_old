<?php

namespace Korko\kTube\Jobs\RefreshSubscriptions;

use Korko\kTube\Account;
use Korko\kTube\Library\YoutubeApiConnected;

class RefreshYoutubeSubscriptions extends RefreshSubscriptions
{
    protected function getChannels(Account $account)
    {
        $yt = new YoutubeApiConnected($account);

        return $yt->getMySubscriptions();
    }
}
