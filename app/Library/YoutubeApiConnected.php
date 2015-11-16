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

        $cursor = $this->worker->getPlaylistsCursor();

        foreach($cursor as $item) {
            $playlist = new Playlist([
                'playlist_id' => $item->id,
                'name'        => $item->snippet->title
            ]);

            $playlist->account = $this->account;

            $playlist->videos = $this->getVideosByPlaylist($playlist->playlist_id);

            $playlists[] = $playlist;
        }

        return $playlists;
    }

    public function getMySubscriptions()
    {
        $channels = new Collection();

        $cursor = $this->worker->getChannelsCursor();

        foreach ($cursor as $item) {
            $channel = new Channel([
                'channel_id' => $item->snippet->resourceId->channelId,
                'name'       => $item->snippet->title
            ]);

            $channel->site = $this->site;

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