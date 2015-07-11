<?php

namespace Korko\kTube\Jobs;

use Carbon\Carbon;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Korko\kTube\Channel;
use Korko\kTube\Video;
use Socialite;

class FetchLastVideos extends Job implements SelfHandling, ShouldQueue {

    use InteractsWithQueue, SerializesModels;

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string
     */
    public $queue = 'videos';

    protected $channel;

    public function __construct(Channel $channel)
    {
        $this->channel = $channel;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $videos = $this->fetchVideos($this->channel);

        // Now save those videos in DB
        $this->saveVideos($this->channel, $videos);

        // Update the channel with those new data
        $this->updateChannel($this->channel, $videos);
    }

    protected function fetchVideos(Channel $channel)
    {
        $videos = new Collection();

        // TODO: Provider should not only be Youtube
        // There might be multiple pages to request so make a loop untile its done
        $pageToken = null;
        do {
            $API_URL = 'https://www.googleapis.com/youtube/v3/search';
            $params = array(
                'part'           => implode(', ', ['id', 'snippet']),
                'channelId'      => $channel->channel_id,
                'order'          => 'date',
                'safeSearch'     => 'none',
                'type'           => 'video',
                'publishedAfter' => $channel->scanned_at->toRfc3339String(),
                'maxResults'     => 50,
                'pageToken'      => $pageToken
            );
            $apiData = json_decode($this->api_get($API_URL, $params));

            foreach ($apiData->items as $item) {
                $videos[] = $this->handleVideoData($channel, $item);
            }

            $pageToken = isset($apiData->nextPageToken) ? $apiData->nextPageToken : null;
        } while (isset($pageToken));

        return $videos;
    }

    // Yep duplicate with FetchAccountSubscriptions::api_get, temporarly
    protected function api_get($url, $params)
    {
        //set the youtube key
        $params['key'] = config('services.youtube.api_key');

        //boilerplates for CURL
        $tuCurl = curl_init();
        curl_setopt($tuCurl, CURLOPT_URL, $url . (strpos($url, '?') === false ? '?' : '') . http_build_query($params));
        if (strpos($url, 'https') === false) {
            curl_setopt($tuCurl, CURLOPT_PORT, 80);
        } else {
            curl_setopt($tuCurl, CURLOPT_PORT, 443);
        }
        curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER, 1);
        $tuData = curl_exec($tuCurl);
        if (curl_errno($tuCurl)) {
            throw new \Exception('Curl Error : ' . curl_error($tuCurl));
        }
        return $tuData;
    }

    protected function handleVideoData(Channel $channel, $item)
    {
        return new Video([
            'channel_id'   => $channel->id,
            'video_id'     => $item->id->videoId,
            'name'         => $item->snippet->title,
            'published_at' => Carbon::parse($item->snippet->publishedAt)
        ]);
    }

    protected function saveVideos(Channel $channel, Collection $videos)
    {
        $videos
            ->chunk(100)
            ->each(function ($videos) use ($channel) {
                $videos = new Collection(array_diff_key(
                    $videos->keyBy('video_id')->all(),
                    Video::where('channel_id', $channel->id)
                        ->whereIn('video_id', $videos->pluck('video_id')->all())
                        ->get(['video_id'])->keyBy('video_id')->all()
                ));

                if (!$videos->isEmpty()) {
                    Video::insert($videos->toArray());
                }
            });
    }

    protected function updateChannel(Channel $channel, Collection $videos)
    {
        // Find the max publication date to limit for next time
        // There can be lag so don't take now as the limit
        $maxPublishedDate = null;
        foreach ($videos as $video) {
            $maxPublishedDate = $maxPublishedDate ? $maxPublishedDate->max($video->published_at) : $video->published_at;
        }

        // Update the channel for next loop
        $channel->update(['scanned_at' => $maxPublishedDate]);
    }
}