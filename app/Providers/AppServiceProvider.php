<?php

namespace Korko\kTube\Providers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\ServiceProvider;
use Korko\kTube\Account;
use Korko\kTube\Channel;
use Korko\kTube\Jobs\RefreshVideos;
use Korko\kTube\Jobs\RefreshSubscriptions;

class AppServiceProvider extends ServiceProvider
{
    use DispatchesJobs;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
