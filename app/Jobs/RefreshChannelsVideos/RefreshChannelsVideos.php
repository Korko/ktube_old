<?php

namespace Korko\kTube\Jobs\RefreshChannelsVideos;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Korko\kTube\Channel;
use Korko\kTube\Jobs\Job;
use Korko\kTube\Library\RefreshChannelsVideos\RefreshChannelsVideos as RefreshChannelsVideosLibrary;

class RefreshChannelsVideos extends Job implements SelfHandling, ShouldQueue
{
    use SerializesModels;

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string
     */
    public $queue = 'videos';

    protected $channel;

    public function __construct(Channel $channel)
    {
        $this->channel = $channel;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        RefreshChannelsVideosLibrary::getInstance($this->channel)->handle();
    }
}
