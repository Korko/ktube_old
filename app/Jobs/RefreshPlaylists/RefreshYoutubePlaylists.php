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

        $api = $this->getApi($account);

        $playlists = $this->allPages(function($pageToken) use ($api) {
	    return $this->fetchYoutubePlaylists($api, $pageToken);
	}, function ($items) use ($api, $account) {
            $playlists = $this->handleYoutubePlaylistsData($account, $items);
            foreach($playlists as &$playlist) {
                $playlist->videos = $this->allPages(function($pageToken) use($api, $playlist) {
                    return $this->fetchYoutubePlaylistItems($api, $playlist->playlist_id, $pageToken);
                }, function ($items) {
                    return $this->handleYoutubePlaylistItemsData($items);
                });
            };
            return $playlists;
        });
dd($playlists);
        return $playlists;
    }

    private function fetchYoutubePlaylists($api, $pageToken = NULL)
    {
        return $api->playlists->listPlaylists('snippet', [
            'mine'           => true,
            'maxResults'     => 50,
            'pageToken'      => $pageToken
        ]);
    }

    private function fetchYoutubePlaylistItems($api, $playlistId, $pageToken = NULL) {
        return $api->playlistItems->listPlaylistItems('snippet', [
            'playlistId'     => $playlistId,
            'maxResults'     => 50,
            'pageToken'      => $pageToken
        ]);
    }

    private function handleYoutubePlaylistsData(Account $account, $items)
    {
        $playlists = [];
        foreach($items as $item) {
            $playlists[] = new Playlist([
                'account_id'   => $account->id,
                'playlist_id'  => $item->id,
                'name'         => $item->snippet->title
            ]);
        }
        return $playlists;
    }

    private function handleYoutubePlaylistItemsData($items)
    {
        $videos = [];
        foreach($items as $item) {//TODO check the channel too
/*            $videos[] = new Video([
                'channel_id'   => $channel->id,
                'video_id'     => $item->id->videoId,
                'name'         => $item->snippet->title,
                'thumbnail'    => $item->snippet->thumbnails->getMedium()->url,
                'published_at' => Carbon::parse($item->snippet->publishedAt)->setTimezone(date_default_timezone_get())
            ]);*/
        }
	return $videos;
    }
}
