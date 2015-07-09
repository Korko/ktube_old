<?php

namespace Korko\kTube\Jobs;

use Alaouy\Youtube\Youtube;
use Carbon\Carbon;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Korko\kTube\Account;
use Korko\kTube\Channel;

class FetchAccountSubscriptions extends Job implements SelfHandling, ShouldQueue {

    use InteractsWithQueue, SerializesModels;

    protected $account;

    /**
     * Create a new job instance.
     *
     * @param  Account  $account
     * @return void
     */
    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $channels = new Collection();

        $pageToken = null;
        do {
            // Provider should not only be Youtube
            $API_URL = 'https://www.googleapis.com/youtube/v3/subscriptions';
            $params = array(
                'part' => implode(', ', ['id', 'snippet']),
                'mine' => 'true',
                'access_token' => $this->account->access_token,
                'maxResults' => 50,
                'pageToken' => $pageToken
            );
            $apiData = json_decode($this->api_get($API_URL, $params));

            foreach($apiData->items as $item) {
                $channel = Channel::firstOrNew([
                    'site_id' => $this->account->site_id,
                    'channel_id' => $item->snippet->resourceId->channelId
                ]);

                $channel->fill([
                    'name' => $item->snippet->title
                ])->save();
                $channels[] = $channel;
            }

            $pageToken = isset($apiData->nextPageToken) ? $apiData->nextPageToken : null;
        } while(isset($pageToken));

        $this->account->channels()->sync($channels->pluck('id')->toArray());
    }

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