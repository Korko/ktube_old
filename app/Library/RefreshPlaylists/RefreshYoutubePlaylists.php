<?php

namespace Korko\kTube\Library\RefreshPlaylists;

use Korko\kTube\Account;
use Korko\kTube\Library\YoutubeApiConnected;

class RefreshYoutubePlaylists extends RefreshPlaylists
{
    private $yt;
    
    public function __construct(Account $account)
    {
        $this->yt = new YoutubeApiConnected($account);

        parent::__construct($account);
    }

    protected function fetchPlaylists()
    {
        return $this->yt->getMyPlaylists();
    }
}
