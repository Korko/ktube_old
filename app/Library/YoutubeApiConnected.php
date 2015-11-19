<?php

namespace Korko\kTube\Library;

use Illuminate\Support\Collection;
use Korko\kTube\Account;

class YoutubeApiConnected extends YoutubeApi
{
    private $account;

    public function __construct(Account $account)
    {
        $this->account = $account;

        parent::__construct();
    }

    protected function getWorker()
    {
        return new YoutubeWorker($this->account->access_token);
    }

    protected function getSite()
    {
        return $this->account->site;
    }

    public function getMyPlaylists()
    {
        $playlists = new Collection();

        $cursor = $this->worker->getPlaylistsCursor(['mine' => true]);

        foreach ($cursor as $item) {
            $playlists[] = [
                'user_id'     => $this->account->user_id,
                'name'        => $item->snippet->title,
                'playlist_id' => $item->id
            ];
        }

        return $playlists;
    }

    public function getMySpecialPlaylists()
    {
        $playlists = new Collection();

        $cursor = $this->worker->getChannelsCursor(['mine' => true], 'contentDetails');

        foreach ($cursor as $item) {
            foreach ($item->contentDetails->relatedPlaylists as $name => $playlistId) {
                $playlists[$name] = [
                    'user_id'     => $this->account->user_id,
                    'name'        => $name,
                    'playlist_id' => $playlistId
                ];
            }
        }

        return $playlists;
    }

    public function getMySubscriptions()
    {
        $channels = new Collection();

        $cursor = $this->worker->getSubscriptionsCursor();

        foreach ($cursor as $item) {
            $channels[] = [
                'site_id'    => $this->site->id,
                'channel_id' => $item->snippet->resourceId->channelId,
                'name'       => $item->snippet->title
            ];
        }

        return $channels;
    }

    public function addPlaylist($name, $videos = array())
    {
        $item = $this->worker->addPlaylist($title);

        foreach ($videos as $video) {
            try {
                $this->worker->addVideoToPlaylist($video->video_id, $item->id);
            } catch (Exception $e) {
                Log::error('Exception thrown when trying to backup videos', ['account' => $this->account, 'video' => $video, 'exception' => $e]);
            }
        }
    }
}
