<?php

namespace Korko\kTube\Jobs\RefreshPlaylists;

use Korko\kTube\Account;
use Korko\kTube\Library\YoutubeApiConnected;

class RefreshYoutubePlaylists extends RefreshPlaylists
{
    protected function fetchPlaylists(Account $account)
    {
        $yt = new YoutubeApiConnected($account);
        return $yt->getMyPlaylists();
    }
}
