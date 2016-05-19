<?php

namespace Korko\kTube\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    use DispatchesJobs;

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \Korko\kTube\Console\Commands\RefreshVideos::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $this->dispatch(new \Korko\kTube\Jobs\RefreshChannelsVideos\RefreshAllChannelsVideos());
        })->description('Refresh channels\' videos')->cron('*/15 * * * *');
    }
}
