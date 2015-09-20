<?php

namespace Korko\kTube\Jobs\RefreshVideos;

use Exception;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Korko\kTube\Channel;
use Korko\kTube\Jobs\Job;

class RefreshAllVideos extends Job implements SelfHandling, ShouldQueue {

    use InteractsWithQueue, SerializesModels, DispatchesJobs;

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string
     */
    public $queue = 'videos';

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Get only channels not updated for 5 minutes
        $channels = Channel::whereRaw('scanned_at = "0000-00-00 00:00:00" OR TIMESTAMPDIFF(MINUTE, scanned_at, NOW()) >= 5')->get();

        // For each of these channels, get the last videos uploaded
        foreach ($channels as $channel) {
            try {
                $this->dispatch(RefreshVideos::getInstance($channel));
            } catch(Exception $e) {
                Log::error($e->getMessage(), ['channel' => $channel]);
            }
        }
    }
}
