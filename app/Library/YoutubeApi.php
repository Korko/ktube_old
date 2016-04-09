<?php

namespace Korko\kTube\Library;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Korko\kTube\Site;

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

        foreach ($cursor as $item) {
            $channel = $this->getChannelById($item->snippet->channelId);

            $videos[] = [
                'channel'      => $channel,
                'video_id'     => $item->snippet->resourceId->videoId,
                'name'         => $item->snippet->title,
                'thumbnail'    => $item->snippet->thumbnails ? $item->snippet->thumbnails->medium->url : null,
                'published_at' => Carbon::parse($item->snippet->publishedAt)->setTimezone(date_default_timezone_get()),
            ];
        }

        return $videos;
    }

    public function getVideosByChannel($channelId, $publishedAfter = null)
    {
        $videos = new Collection();

        $cursor = $this->worker->getSearchCursor(['channelId' => $channelId, 'publishedAfter' => $publishedAfter]);

        foreach ($cursor as $item) {
            $videos[] = [
                'video_id'     => $item->id->videoId,
                'name'         => $item->snippet->title,
                'thumbnail'    => $item->snippet->thumbnails ? $item->snippet->thumbnails->medium->url : null,
                'published_at' => Carbon::parse($item->snippet->publishedAt)->setTimezone(date_default_timezone_get()),
            ];
        }

        return $videos;
    }

    public function getChannelById($channelId)
    {
        $cursor = $this->worker->getChannelsCursor(['id' => $channelId]);

        if (!$cursor->valid()) {
            throw new Exception('Cannot find channel '.$channelId);
        }

        $item = $cursor->current();

        return [
            'site_id'    => $this->site->id,
            'channel_id' => $item->id,
            'name'       => $item->snippet->title,
        ];
    }
}
