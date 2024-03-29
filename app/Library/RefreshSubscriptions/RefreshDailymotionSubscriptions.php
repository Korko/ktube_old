<?php

namespace Korko\kTube\Library\RefreshSubscriptions;

use Illuminate\Support\Collection;
use Korko\kTube\Account;
use Korko\kTube\Channel;
use Korko\kTube\Jobs\DailymotionJob;

class RefreshDailymotionSubscriptions extends RefreshSubscriptions
{
    use DailymotionJob;

    protected function getChannels()
    {
        $channels = new Collection();

        foreach ($this->getDailymotionRawChannels($this->account) as $item) {
            $channels[] = new Channel([
                'site_id'    => $this->account->site_id,
                'channel_id' => $item['id'],
                'name'       => $item['screenname'],
            ]);
        }

        return $channels;
    }

    protected function getDailymotionRawChannels(Account $account)
    {
        $page = 0;

        $api = $this->getApi($account);

        do {
            $data = $api->get('/user/me/following', [
                'fields' => 'id,screenname',
                'limit'  => 50,
                'page'   => ++$page,
            ]);

            foreach ($data['list'] as $item) {
                yield $item;
            }
        } while ($data['has_more']);
    }
}
