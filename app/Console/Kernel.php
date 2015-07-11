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
        })->description('Refresh tokens')->everyMinute();

        $schedule->call(function () {
            $this->dispatch(new RefreshAllSubscriptions());
            $this->dispatch(new RefreshAllVideos());
        })->description('Refresh channels and videos')->cron('*/15 * * * *')->withoutOverlapping();

        $schedule->call(function () {
            //$this->dispatch(new BackupVideos())->onQueue('playlists');
        })->description('Backups yesterday\'s videos for users in holidays')->dailyAt('12:00');
    }
}
