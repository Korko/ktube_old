<?php

namespace Korko\kTube\Library\RefreshVideos;

use Korko\kTube\Account;
use Korko\kTube\Library\YoutubeApiConnected;

class RefreshYoutubeVideos extends RefreshVideos
{
    private $yt;

    public function __construct(Account $account)
    {
        $this->yt = new YoutubeApiConnected($account);

        parent::__construct($account);
    }

    protected function fetchVideos()
    {
        $publishedAfter = $this->channel->scanned_at->setTimezone('UTC')->toRfc3339String();

        return $this->yt->getVideosByChannel($this->channel->channel_id, $publishedAfter);
    }
}
