<?php

namespace Korko\kTube\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Korko\kTube\Jobs\BackupAccount\BackupAllAccounts;
use Korko\kTube\Jobs\RefreshChannelsVideos\RefreshAllChannelsVideos;
use Korko\kTube\Jobs\RefreshPlaylists\RefreshAllPlaylists;
use Korko\kTube\Jobs\RefreshSubscriptions\RefreshAllSubscriptions;
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
        \Korko\kTube\Console\Commands\BackupAccount::class,
        \Korko\kTube\Console\Commands\RefreshPlaylists::class,
        \Korko\kTube\Console\Commands\RefreshSubscriptions::class,
        \Korko\kTube\Console\Commands\RefreshAccountToken::class
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

    }
}
