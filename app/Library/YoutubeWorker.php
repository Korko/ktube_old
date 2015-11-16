<?php

namespace Korko\kTube\Library;

use Closure;

class YoutubeWorker
{
    private $api;

    public function __construct($accessToken)
    {
        $this->api = YoutubeLib::getApi($accessToken);
    }

    public function getPlaylistsCursor()
    {
        return $this->allPagesCursor(function($pageToken) {
            return $this->api->playlists->listPlaylists('snippet', [
                'mine'       => true,
                'maxResults' => 50,
                'pageToken'  => $pageToken
            ]);
        });
    }

    public function getPlaylistItemsCursor($playlistId)
    {
        return $this->allPagesCursor(function($pageToken) use ($playlistId) {
            return $this->api->playlistItems->listPlaylistItems('snippet', [
                'playlistId' => $playlistId,
                'maxResults' => 50,
                'pageToken'  => $pageToken
            ]);
        });
    }

    public function getChannel($channelId)
    {
        $data = $this->api->channels->listChannels('snippet', [
            'id' => $channelId
        ]);

        if(count($data->items) === 0) {
            throw new Exception('Cannot find channel '.$channelId);
        }

        return $data->items[0];
    }

    public function getChannelsCursor()
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