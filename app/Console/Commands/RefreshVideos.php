<?php

namespace Korko\kTube\Console\Commands;

use Illuminate\Console\Command;
use Korko\kTube\Channel;
use Korko\kTube\Library\RefreshChannelsVideos\RefreshChannelsVideos as RefreshChannelsVideosLibrary;

class RefreshVideos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh:videos {channel?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh a channel videos.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $channelId = $this->argument('channel');

        if($channelId !== null) {
            $channel = Channel::findOrFail($channelId);

            RefreshChannelsVideosLibrary::getInstance($channel)->handle();
	} else {
            $channels = Channel::all();

            foreach ($channels as $channel) {
                RefreshChannelsVideosLibrary::getInstance($channel)->handle();
            }
	}
    }
}
