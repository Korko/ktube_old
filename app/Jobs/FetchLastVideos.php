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
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $channels = Channel::havingRaw('TIMESTAMPDIFF(MINUTE, scanned_at, NOW()) > 5');

        foreach($channels as $channel) {
            $videos = new Collection();

            $now = Carbon::now();

            $pageToken = null;
            do {
                // Provider should not only be Youtube
                $API_URL = 'https://www.googleapis.com/youtube/v3/search';
                $params = array(
                    'part' => implode(', ', ['id', 'snippet']),
                    'channelId' => $channel->channel_id,
                    'order' => 'date',
                    'safeSearch' => 'none',
                    'type' => 'video',
                    'publishedAfter' => $channel->scanned_at->toRfc3339String(),
                    'maxResults' => 50,
                    'pageToken' => $pageToken
                );
                $apiData = json_decode($this->api_get($API_URL, $params));

                foreach($apiData->items as $item) {
                    $video = Video::firstOrNew([
                        'channel_id' => $channel->id,
                        'video_id' => $item->id->videoId,
                    ]);

                    $video->fill([
                        'name' => $item->snippet->title,
                        'published_at' => Carbon::parse($item->snippet->publishedAt)
                    ])->save();

                    $videos[] = $video;
                }

                $pageToken = isset($apiData->nextPageToken) ? $apiData->nextPageToken : null;
            } while(isset($pageToken));

            $channel->update(['scanned_at' => $now]);
        }
    }

    // Yep duplicate with FetchAccountSubscriptions::api_get, temporarly
    public function api_get($url, $params) {
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
}