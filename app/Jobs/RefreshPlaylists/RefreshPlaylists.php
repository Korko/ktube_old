<?php

namespace Korko\kTube\Jobs\RefreshPlaylists;

use Exception;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Korko\kTube\Account;
use Korko\kTube\Jobs\Job;
use Korko\kTube\Playlist;

abstract class RefreshPlaylists extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string
     */
    public $queue = 'playlists';

    public static function getInstance(Account $account)
    {
            switch ($account->site->provider) {
                case 'google':
                    return new RefreshYoutubePlaylists($account);
                    break;

                default:
                    throw new Exception('Account provider not managed');
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
        $playlists = $this->fetchPlaylists($this->account);

        // Now save those playlists in DB
        $this->savePlaylists($this->account, $playlists);
    }

    /**
     * Fetch new playlists from this specific account
     * @param  Account $account [description]
     * @return Collection       List of new playlists to add to this account
     */
    abstract protected function fetchPlaylists(Account $account);

    /**
     * Save those playlists (removes the duplicates)
     * @param  Account    $account   [description]
     * @param  Collection $playlists [description]
     * @return [type]                [description]
     */
    protected function savePlaylists(Account $account, Collection $playlists)
    {/*
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
    */}
}
