<?php

namespace Korko\kTube\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Korko\kTube\Jobs\BackupAccount\BackupAllAccounts;
use Korko\kTube\Jobs\RefreshSubscriptions\RefreshAllSubscriptions;
use Korko\kTube\Jobs\RefreshVideos\RefreshAllVideos;
use Korko\kTube\Jobs\RefreshToken\RefreshTokens;

class Kernel extends ConsoleKernel
{
    use DispatchesJobs;

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \Korko\kTube\Console\Commands\RefreshPlaylists::class,
        \Korko\kTube\Console\Commands\RefreshSubscriptions::class
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
            $this->dispatch(new RefreshAllChannelsVideos());
        })->description('Refresh channels and videos')->cron('*/15 * * * *');

        $schedule->call(function () {
            $this->dispatch(new RefreshAllPlaylists());
        })->description('Refresh playlists')->cron('*/15 * * * *');

        $schedule->call(function () {
            $this->dispatch(new BackupAllAccounts());
        })->description('Backups yesterday\'s videos for users in holidays')->dailyAt('12:00');
    }
}
