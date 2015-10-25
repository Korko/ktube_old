<?php

namespace Korko\kTube\Jobs\RefreshPlaylists;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Korko\kTube\Account;
use Korko\kTube\Playlist;
use Korko\kTube\Jobs\YoutubeJob;

class RefreshYoutubePlaylists extends RefreshPlaylists
{
    use YoutubeJob;

    protected function fetchPlaylists(Account $account)
    {
        $playlists = new Collection();

        $api = $this->getApi();

        // There might be multiple pages to request so make a loop untile its done
        $pageToken = null;
        do {
            $items = $api->playlists->listPlaylists('snippet', [
                'mine'           => true,
                'maxResults'     => 50,
                'pageToken'      => $pageToken
            ]);

            foreach ($items as $item) {
                $playlists[] = $this->handlePlaylistsData($account, $item);
            }

            $pageToken = $items->nextPageToken;
        } while ($pageToken !== null);

        return $playlists;
    }

    protected function handlePlaylistData(Account $account, $item)
    {
        return new Playlist([
            'account_id'   => $account->id,
            'playlist_id'  => $item->id,
            'name'         => $item->snippet->title,
            //'thumbnail'    => $item->snippet->thumbnails->getMedium()->url,
        ]);
    */}
}
