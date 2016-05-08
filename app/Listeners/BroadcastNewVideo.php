<?php

namespace Korko\kTube\Listeners;

use Illuminate\Support\Facades\Redis;
use Korko\kTube\Events\NewVideo;

class BroadcastNewVideo
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param NewVideo $event
     *
     * @return void
     */
    public function handle(NewVideo $event)
    {
        $redis = Redis::connection();
        $video = $event->getVideo();

        // Ok so there's a new video, for each connected users, check if it may interest them
        $interestedUsers = $this->getInterestedUsers($video);
        foreach ($interestedUsers as $user) {
            $redis->publish('new_video', ['user' => $user->id, 'video' => $video]);
        }
    }

    /**
     * Get a list of users who may be interested by a specific video.
     * Limit those users to connected ones.
     *
     * @param Video $video [description]
     *
     * @return array List of connected users interested by this video
     */
    protected function getInterestedUsers(Video $video)
    {
        // TODO
        return [User::find(1)];
    }
}
