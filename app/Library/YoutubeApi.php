<?php

namespace Korko\kTube\Library;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Korko\kTube\Channel;
use Korko\kTube\Playlist;
use Korko\kTube\Site;
use Korko\kTube\Video;

class YoutubeApi
{
    protected $site;

    protected $worker;

    public function __construct()
    {
        $this->site = $this->getSite();

        $this->worker = $this->getWorker();
    }

    protected function getWorker()
    {
        return new YoutubeWorker();
    }

    protected function getSite()
    {
        return Site::where('provider', 'google')->findOrFail();
    }

    public function getVideosByPlaylist($playlistId)
    {
        $videos = new Collection();

        $cursor = $this->worker->getPlaylistItemsCursor($playlistId);

        foreach($cursor as $item) {
            $video = new Video([
                'video_id'     => $item->snippet->resourceId->videoId,
                'name'         => $item->snippet->title,
                'thumbnail'    => $item->snippet->thumbnails ? $item->snippet->thumbnails->medium->url : null,
                'published_at' => Carbon::parse($item->snippet->publishedAt)->setTimezone(date_default_timezone_get())
            ]);

            $video->channel = $this->getChannelById($item->snippet->channelId);

            $videos[] = $video;
        }

        return $videos;
    }

    public function getChannelById($channelId)
    {
        $item = $this->worker->getChannel($channelId);

        $channel = new Channel([
            'channel_id' => $item->id,
            'name'       => $item->snippet->title
        ]);

        $channel->site = $this->site;

        return $channel;
    }
}