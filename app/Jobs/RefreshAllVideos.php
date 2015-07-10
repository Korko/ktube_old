<?php

namespace Korko\kTube\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Korko\kTube\Channel;
use Korko\kTube\Jobs\FetchLastVideos;

class RefreshAllVideos extends Job implements SelfHandling, ShouldQueue {

    use InteractsWithQueue, SerializesModels, DispatchesJobs;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Get only channels not updated for 5 minutes
        $channels = Channel::whereRaw('scanned_at = "0000-00-00 00:00:00" OR TIMESTAMPDIFF(MINUTE, scanned_at, NOW()) >= 5')->where('id', 1)->get();

        // For each of these channels, get the last videos uploaded
        foreach ($channels as $channel) {
            $this->dispatch(new FetchLastVideos($channel));
        }
    }
}