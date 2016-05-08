<?php

namespace Korko\kTube\Events;

use Illuminate\Queue\SerializesModels;

class NewVideo extends Event
{
    use SerializesModels;

    protected $video;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }

    public function getVideo()
    {
        return $this->video;
    }
}
