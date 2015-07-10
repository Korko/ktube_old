<?php

namespace Korko\kTube\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Korko\kTube\Jobs\RefreshAllSubscriptions;
use Korko\kTube\Jobs\RefreshAllVideos;
use Korko\kTube\Jobs\RefreshTokens;

class Kernel extends ConsoleKernel
{
    use DispatchesJobs;

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \Korko\kTube\Console\Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $this->dispatch(new RefreshTokens());
        })->everyMinute();

        $schedule->call(function () {
            $this->dispatch(new RefreshAllVideos());
            $this->dispatch(new RefreshAllSubscriptions());
        })->cron('*/15 * * * *');

        $schedule->call(function () {
            //$this->dispatch(new BackupVideos());
        })->dailyAt('12:00');
    }
}
