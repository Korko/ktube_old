<?php

namespace Korko\kTube\Jobs\RefreshSubscriptions;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Korko\kTube\Account;
use Korko\kTube\Channel;

class RefreshYoutubeSubscriptions extends RefreshSubscriptions
{
    protected function getChannels(Account $account)
    {
        $channels = new Collection();

        foreach ($this->getYoutubeRawChannels($account) as $item) {
            $channels[] = new Channel([
                'site_id'    => $account->site_id,
                'channel_id' => $item->snippet->resourceId->channelId,
                'name'       => $item->snippet->title
            ]);
        }

        return $channels;
    }

    protected function getRawChannels(Account $account)
    {
        $pageToken = null;

        $api = $this->getYoutubeApi($account);

        do {
            $items = $api->subscriptions->listSubscriptions('snippet', [
                'mine'       => 'true',
                'maxResults' => 50,
                'pageToken'  => $pageToken
            ]);

            foreach ($items as $item) {
                yield $item;
            }

            $pageToken = $items->nextPageToken;
        } while ($pageToken !== null);
    }
}