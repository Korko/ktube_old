<?php

namespace Korko\kTube\Jobs\RefreshVideos;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Korko\kTube\Channel;
use Korko\kTube\Video;
use Korko\kTube\Jobs\YoutubeJob;

class RefreshYoutubeVideos extends RefreshVideos
{
    use YoutubeJob;

    protected function fetchVideos(Channel $channel)
    {
        $videos = new Collection();

        $api = $this->getYoutubeApi();

        // There might be multiple pages to request so make a loop untile its done
        $pageToken = null;
        do {
            $items = $api->search->listSearch('snippet', [
                'channelId'      => $channel->channel_id,
                'order'          => 'date',
                'safeSearch'     => 'none',
                'type'           => 'video',
                'publishedAfter' => $channel->scanned_at->setTimezone('UTC')->toRfc3339String(),
                'maxResults'     => 50,
                'pageToken'      => $pageToken
            ]);

            foreach ($items as $item) {
                $videos[] = $this->handleVideoData($channel, $item);
            }

            $pageToken = $items->nextPageToken;
        } while ($pageToken !== null);

        return $videos;
    }

    protected function handleVideoData(Channel $channel, $item)
    {
        return new Video([
            'channel_id'   => $channel->id,
            'video_id'     => $item->id->videoId,
            'name'         => $item->snippet->title,
            'thumbnail'    => $item->snippet->thumbnails->getMedium()->url,
            'published_at' => Carbon::parse($item->snippet->publishedAt)->setTimezone(date_default_timezone_get())
        ]);
    }
}
