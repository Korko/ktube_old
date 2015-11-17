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
        return Site::where('provider', 'google')->firstOrFail();
    }

    public function getVideosByPlaylist($playlistId)
    {
        $videos = new Collection();

        $cursor = $this->worker->getPlaylistItemsCursor(['playlistId' => $playlistId]);

        foreach($cursor as $item) {
            $channel = $this->getChannelById($item->snippet->channelId);//TODO cascading add

            $video = new Video([
                'channel_id'   => $channel->id,
                'video_id'     => $item->snippet->resourceId->videoId,
                'name'         => $item->snippet->title,
                'thumbnail'    => $item->snippet->thumbnails ? $item->snippet->thumbnails->medium->url : null,
                'published_at' => Carbon::parse($item->snippet->publishedAt)->setTimezone(date_default_timezone_get())
            ]);

            $videos[] = $video;
        }

        return $videos;
    }

    public function getVideosByChannel(Channel $channel, $publishedAfter = null)
    {
        $videos = new Collection();

        $cursor = $this->worker->getSearchCursor(['channelId' => $channel->channel_id, 'publishedAfter' => $publishedAfter]);

        foreach($cursor as $item) {
            $video = new Video([
                'channel_id'   => $channel->id,
                'video_id'     => $item->snippet->resourceId->videoId,
                'name'         => $item->snippet->title,
                'thumbnail'    => $item->snippet->thumbnails ? $item->snippet->thumbnails->medium->url : null,
                'published_at' => Carbon::parse($item->snippet->publishedAt)->setTimezone(date_default_timezone_get())
            ]);

            $videos[] = $video;
        }

        return $videos;
    }

    public function getChannelById($channelId)
    {
        $cursor = $this->worker->getChannelsCursor(['id' => $channelId]);

        if(!$cursor->valid()) {
            throw new Exception('Cannot find channel '.$channelId);
        }

        $item = $cursor->current();

        $channel = new Channel([
            'site_id'    => $this->site->id,
            'channel_id' => $item->id,
            'name'       => $item->snippet->title
        ]);

        return $channel;
    }
}