<?php

namespace Korko\kTube\Library\RefreshPlaylists;

use Illuminate\Support\Collection;
use Korko\kTube\Account;
use Korko\kTube\Exceptions\InvalidProviderException;
use Korko\kTube\Playlist;

abstract class RefreshPlaylists
{
    public static function getInstance(Account $account)
    {
            switch ($account->site->provider) {
                case 'google':
                    return new RefreshYoutubePlaylists($account);
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
        $playlists = $this->fetchPlaylists();

        // Now save those playlists in DB
        $this->savePlaylists($playlists);

        return $playlists;
    }

    /**
     * Fetch new playlists from this specific account
     * @return Collection       List of new playlists to add to this account
     */
    abstract protected function fetchPlaylists();

    /**
     * Save those playlists (removes the duplicates)
     * @param  Collection $playlists [description]
     */
    protected function savePlaylists(Collection $playlists)
    {
        $newPlaylists = array_diff_key(
            $playlists->keyBy('playlist_id')->all(),
            Playlist::where('account_id', $this->account->id)
                ->whereIn('playlist_id', $playlists->pluck('playlist_id')->all())
                ->get(['playlist_id'])->keyBy('playlist_id')->all()
        );

        if ($newPlaylists !== array()) {
            Playlist::insert($newPlaylists);
        }
    }
}
