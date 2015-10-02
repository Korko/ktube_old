<?php

namespace Korko\kTube\Jobs\RefreshVideos;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Korko\kTube\Channel;
use Korko\kTube\Video;
use Korko\kTube\Jobs\DailymotionJob;

class RefreshDailymotionVideos extends RefreshVideos
{
    use DailymotionJob;

    protected function fetchVideos(Channel $channel)
    {
        $videos = new Collection();

        $api = $this->getApi();

        // There might be multiple pages to request so make a loop untile its done
        $page = 0;
        do {
            $data = $api->get('/user/'.$channel->channel_id.'/videos', [
                'fields'         => 'id,title,thumbnail_360_url,created_time',
                'sort'           => 'recent',
                'created_after'  => $channel->scanned_at->setTimezone('UTC')->toRfc3339String(),
                'limit'          => 50,
                'page'           => ++$page
            ]);

            foreach ($data['list'] as $item) {
                $videos[] = $this->handleVideoData($channel, $item);
            }
        } while ($data['has_more']);

        return $videos;
    }

    protected function handleVideoData(Channel $channel, $item)
    {
        return new Video([
            'channel_id'   => $channel->id,
            'video_id'     => $item['id'],
            'name'         => $item['title'],
            'thumbnail'    => $item['thumbnail_360_url'],
            'published_at' => Carbon::createFromTimeStamp($item['created_time'])->setTimezone(date_default_timezone_get())
        ]);
    }
}
