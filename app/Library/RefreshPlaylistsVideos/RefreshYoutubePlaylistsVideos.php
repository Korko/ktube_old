<?php

namespace Korko\kTube\Library\RefreshPlaylistsVideos;

use Korko\kTube\Account;
use Korko\kTube\Library\YoutubeApiConnected;
use Korko\kTube\Playlist;

class RefreshYoutubePlaylistsVideos extends RefreshPlaylistsVideos
{
    private $yt;

    public function __construct(Account $account, Playlist $playlist, $playlistId)
    {
        $this->yt = new YoutubeApiConnected($account);

        parent::__construct($account, $playlist, $playlistId);
    }

    protected function fetchVideos()
    {
        return $this->yt->getVideosByPlaylist($this->playlistId);
    }
}
