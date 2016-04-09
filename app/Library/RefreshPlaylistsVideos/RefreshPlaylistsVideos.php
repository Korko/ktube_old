<?php

namespace Korko\kTube\Library\RefreshPlaylistsVideos;

use Illuminate\Support\Collection;
use Korko\kTube\Account;
use Korko\kTube\Channel;
use Korko\kTube\Exceptions\InvalidProviderException;
use Korko\kTube\Playlist;
use Korko\kTube\Video;

abstract class RefreshPlaylistsVideos
{
    public static function getInstance(Account $account, Playlist $playlist, $playlistId)
    {
        switch ($account->site->provider) {
            case 'google':
                return new RefreshYoutubePlaylistsVideos($account, $playlist, $playlistId);
                break;

            default:
                throw new InvalidProviderException('Account provider not managed');
        }
    }

    protected $account;
    protected $playlist;
    protected $playlistId;

    public function __construct(Account $account, Playlist $playlist, $playlistId)
    {
        $this->account = $account;
        $this->playlist = $playlist;
        $this->playlistId = $playlistId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $videos = $this->fetchVideos();

        // Now save those videos in DB
        $this->saveVideos($videos);

        return $videos;
    }

    /**
     * Fetch new videos from this specific channel.
     *
     * @return Collection List of new videos to add to this channel
     */
    abstract protected function fetchVideos();

    /**
     * Save those videos (removes the duplicates).
     *
     * @param Collection $videos [description]
     */
    protected function saveVideos(Collection $videos)
    {
        $videoIds = [];

        foreach ($videos as $key => $video) {
            $channel = Channel::updateOrCreate([
                'channel_id' => $video['channel']['channel_id'],
                'site_id'    => $this->account->site_id,
            ], $video['channel']);

            unset($video['channel']);
            $video = Video::updateOrCreate([
                'video_id'   => $video['video_id'],
                'channel_id' => $channel->id,
            ], $video);

            $videoIds[] = $video->id;
        }

        $this->playlist->videos()->sync($videoIds);
    }
}
