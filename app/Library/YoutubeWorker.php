<?php

namespace Korko\kTube\Library;

use Closure;

class YoutubeWorker
{
    private $api;

    public function __construct($accessToken = null)
    {
        $this->api = YoutubeLib::getApi($accessToken);
    }

    public function getPlaylistsCursor(Array $filters)
    {
        return $this->allPagesCursor(function($pageToken) use($filters) {
            return $this->api->playlists->listPlaylists('snippet', [
                'maxResults' => 50,
                'pageToken'  => $pageToken
            ] + $filters);
        });
    }

    public function getPlaylistItemsCursor(Array $filters)
    {
        return $this->allPagesCursor(function($pageToken) use ($filters) {
            return $this->api->playlistItems->listPlaylistItems('snippet', [
                'maxResults' => 50,
                'pageToken'  => $pageToken
            ] + $filters);
        });
    }

    public function getSearchCursor(Array $filters)
    {
        return $this->allPagesCursor(function($pageToken) use ($filters) {
            return $this->api->search->listSearch('snippet', [
                'order'      => 'date',
                'safeSearch' => 'none',
                'type'       => 'video',
                'maxResults' => 50,
                'pageToken'  => $pageToken
            ] + $filters);
        });
    }

    public function getChannelsCursor(Array $filters)
    {
        return $this->allPagesCursor(function($pageToken) use ($filters) {
            return $this->api->channels->listChannels('snippet', [
                'maxResults' => 50,
                'pageToken'  => $pageToken
            ] + $filters);
        });
    }

    public function getSubscriptionsCursor()
    {
        return $this->allPagesCursor(function($pageToken) {
            return $this->api->subscriptions->listSubscriptions('snippet', [
                'mine'       => 'true',
                'maxResults' => 50,
                'pageToken'  => $pageToken
            ]);
        });
    }

    public function addPlaylist($title, $description = '', $status = 'private')
    {
        $youTubePlaylist = YoutubeLib::getPlaylist($title, $description, $status);

        return $this->api->playlists->insert('snippet,status', $youTubePlaylist);
    }

    public function addVideoToPlaylist($videoId, $playlistId)
    {
        $playlistItem = YoutubeLib::getPlaylistItem($videoId, $playlistId);

        return $this->api->playlistItems->insert('snippet,contentDetails', $playlistItem, []);
    }

    private function allPagesCursor(Closure $fetch)
    {
        // There might be multiple pages to request so make a loop untile its done
        $pageToken = null;
        do {
            $data = $fetch($pageToken);

            foreach($data as $item) {
                yield $item;
            }

            $pageToken = $data->nextPageToken;
        } while ($pageToken !== null);
    }
}