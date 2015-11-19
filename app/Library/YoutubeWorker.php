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

    public function getPlaylistsCursor(Array $filters, $select = 'snippet')
    {
        return $this->allPagesCursor(function ($pageToken) use ($select, $filters) {
            return $this->api->playlists->listPlaylists($select, [
                'maxResults' => 50,
                'pageToken'  => $pageToken
            ] + $filters);
        });
    }

    public function getPlaylistItemsCursor(Array $filters, $select = 'snippet')
    {
        return $this->allPagesCursor(function ($pageToken) use ($select, $filters) {
            return $this->api->playlistItems->listPlaylistItems($select, [
                'maxResults' => 50,
                'pageToken'  => $pageToken
            ] + $filters);
        });
    }

    public function getSearchCursor(Array $filters, $select = 'snippet')
    {
        return $this->allPagesCursor(function ($pageToken) use ($select, $filters) {
            return $this->api->search->listSearch($select, [
                'order'      => 'date',
                'safeSearch' => 'none',
                'type'       => 'video',
                'maxResults' => 50,
                'pageToken'  => $pageToken
            ] + $filters);
        });
    }

    public function getChannelsCursor(Array $filters, $select = 'snippet')
    {
        return $this->allPagesCursor(function ($pageToken) use ($select, $filters) {
            return $this->api->channels->listChannels($select, [
                'maxResults' => 50,
                'pageToken'  => $pageToken
            ] + $filters);
        });
    }

    public function getSubscriptionsCursor($select = 'snippet')
    {
        return $this->allPagesCursor(function ($pageToken) use($select) {
            return $this->api->subscriptions->listSubscriptions($select, [
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

            foreach ($data as $item) {
                yield $item;
            }

            $pageToken = $data->nextPageToken;
        } while ($pageToken !== null);
    }
}
