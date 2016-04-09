<?php

namespace Korko\kTube\Library\RefreshSubscriptions;

use Illuminate\Support\Collection;
use Korko\kTube\Account;
use Korko\kTube\Channel;
use Korko\kTube\Exceptions\InvalidProviderException;

abstract class RefreshSubscriptions
{
    public static function getInstance(Account $account)
    {
        switch ($account->site->provider) {
            case 'google':
                return new RefreshYoutubeSubscriptions($account);
                break;

            case 'dailymotion':
                return new RefreshDailymotionSubscriptions($account);
                break;

            default:
                throw new InvalidProviderException('Account provider not managed');
        }
    }

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
        $channels = $this->getChannels();

        $channels = $this->saveChannels($channels);

        $this->account->channels()->sync($channels->pluck('id')->toArray());

        return $channels;
    }

    abstract protected function getChannels();

    protected function saveChannels(Collection $channels)
    {
        $channels = $channels
            ->chunk(200)
            ->map(function ($channels) {
                return $this->findOrCreate($channels);
            })
            ->collapse();

        return $channels;
    }

    protected function findOrCreate(Collection $channels)
    {
        // Find all already existing channels
        $oldChannels = Channel::where('site_id', $this->account->site_id)
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
            $newChannels = Channel::where('site_id', $this->account->site_id)
                ->whereIn('channel_id', $newChannels->pluck('channel_id')->all())
                ->get();
        }

        // Merge both old and new channels so that we have all of them
        return $oldChannels->merge($newChannels);
    }
}
