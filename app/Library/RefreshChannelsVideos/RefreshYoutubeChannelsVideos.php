<?php

namespace Korko\kTube\Library\RefreshChannelsVideos;

use Korko\kTube\Channel;
use Korko\kTube\Library\YoutubeApi;

class RefreshYoutubeChannelsVideos extends RefreshChannelsVideos
{
    private $yt;

    public function __construct(Channel $channel)
    {
        $this->yt = new YoutubeApi();

        parent::__construct($channel);
    }

    protected function fetchVideos()
    {
        $publishedAfter = $this->channel->scanned_at->setTimezone('UTC')->toRfc3339String();

        return $this->yt->getVideosByChannel($this->channel->channel_id, $publishedAfter);
    }
}
