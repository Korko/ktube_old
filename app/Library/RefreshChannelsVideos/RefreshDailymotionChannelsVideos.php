<?php

namespace Korko\kTube\Library\RefreshChannelsVideos;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Korko\kTube\Channel;
use Korko\kTube\Jobs\DailymotionJob;
use Korko\kTube\Video;

class RefreshDailymotionChannelsVideos extends RefreshChannelsVideos
{
    use DailymotionJob;

    protected function fetchVideos()
    {
        $videos = new Collection();

        $api = $this->getApi();

        // There might be multiple pages to request so make a loop untile its done
        $page = 0;
        do {
            $data = $api->get('/user/'.$this->channel->channel_id.'/videos', [
                'fields'         => 'id,title,thumbnail_180_url,created_time',
                'sort'           => 'recent',
                'created_after'  => $this->channel->scanned_at->setTimezone('UTC')->toRfc3339String(),
                'limit'          => 50,
                'page'           => ++$page,
            ]);

            foreach ($data['list'] as $item) {
                $videos[] = $this->handleVideoData($item);
            }
        } while ($data['has_more']);

        return $videos;
    }

    protected function handleVideoData($item)
    {
        return new Video([
            'channel_id'   => $this->channel->id,
            'video_id'     => $item['id'],
            'name'         => $item['title'],
            'thumbnail'    => $item['thumbnail_180_url'],
            'published_at' => Carbon::createFromTimeStamp($item['created_time'])->setTimezone(date_default_timezone_get()),
        ]);
    }
}
