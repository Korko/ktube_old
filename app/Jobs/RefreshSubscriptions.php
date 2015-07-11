<?php

namespace Korko\kTube\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Korko\kTube\Account;
use Korko\kTube\Channel;

class RefreshSubscriptions extends Job implements SelfHandling, ShouldQueue {

    use InteractsWithQueue, SerializesModels;

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string
     */
    public $queue = 'channels';

    protected $account;

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
        $channels = $this->getChannels($this->account);

        $channels = $this->saveChannels($this->account, $channels);

        $this->account->channels()->sync($channels->pluck('id')->toArray());
    }

    protected function getChannels(Account $account)
    {
        $channels = new Collection();

        switch ($account->site->name) {
            case 'google':
                $channels = $this->getYoutubeChannels($account);
                break;
            default:
                throw new Exception('Undefined site '.var_export($account->site->name, TRUE));
        }

        return $channels;
    }

    protected function getYoutubeChannels(Account $account)
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

    protected function getYoutubeRawChannels(Account $account)
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

    protected function saveChannels(Account $account, Collection $channels)
    {
        $channels = $channels
            ->chunk(200)
            ->map(function($channels) use ($account) {
                return $this->findOrCreate($account, $channels);
            })
            ->collapse();

        return $channels;
    }

    protected function findOrCreate(Account $account, Collection $channels)
    {
        // Find all already existing channels
        $oldChannels = Channel::where('site_id', $account->site_id)
            ->whereIn('channel_id', $channels->pluck('channel_id')->all())
            ->get();

        // Remove old channels from the full list
        $newChannels = new Collection(array_diff_key(
            $channels->keyBy('channel_id')->all(),
            $oldChannels->keyBy('channel_id')->all()
        ));

        if (!$newChannels->isEmpty()) {
            // Save new channels
            Channel::insert($newChannels->toArray());

            // Get those new channels ids
            $newChannels = Channel::where('site_id', $account->site_id)
                ->whereIn('channel_id', $newChannels->pluck('channel_id')->all())
                ->get();
        }

        // Merge both old and new channels so that we have all of them
        return $oldChannels->merge($newChannels);
    }
}