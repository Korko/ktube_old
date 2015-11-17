<?php

namespace Korko\kTube\Library;

use Illuminate\Support\Collection;
use Korko\kTube\Account;
use Korko\kTube\Channel;
use Korko\kTube\Playlist;

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

        foreach($cursor as $item) {
            $playlist = new Playlist([
                'accoun_id'   => $this->account->id,
                'playlist_id' => $item->id,
                'name'        => $item->snippet->title
            ]);

            $playlists[] = $playlist;
        }

        return $playlists;
    }

    public function getMySubscriptions()
    {
        $channels = new Collection();

        $cursor = $this->worker->getSubscriptionsCursor();

        foreach ($cursor as $item) {
            $channel = new Channel([
                'site_id'    => $this->site->id,
                'channel_id' => $item->snippet->resourceId->channelId,
                'name'       => $item->snippet->title
            ]);

            $channels[] = $channel;
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