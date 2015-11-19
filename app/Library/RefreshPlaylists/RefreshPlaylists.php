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
        $playlists = $this->savePlaylists($playlists);

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
        $accountPlaylists = $this->account->playlists;

        foreach ($playlists as $key => $playlistData) {
            $playlist = $accountPlaylists->first(function ($key, $accountPlaylist) use($playlistData) {
                return ($accountPlaylist->pivot->playlist_site_id === $playlistData['playlist_id']);
            });

            if ($playlist === null) {
                $playlist = Playlist::create([
                    'user_id' => $playlistData['user_id'],
                    'name'    => $playlistData['name'],
                ]);

                $playlist->accounts()->save($this->account, ['playlist_site_id' => $playlistData['playlist_id']]);
            }

            $playlists[$key] = $playlist;
        }

        return $playlists;
    }
}
