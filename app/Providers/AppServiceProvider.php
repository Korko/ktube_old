<?php

namespace Korko\kTube\Providers;

use Blade;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\ServiceProvider;
use Korko\kTube\Account;
use Korko\kTube\Channel;
use Korko\kTube\Jobs\RefreshVideos\RefreshVideos;
use Korko\kTube\Jobs\RefreshSubscriptions\RefreshSubscriptions;

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
        // If Queue connection is sync, it may fail due to race condition
        if (config('queue.default') !== 'sync') {
            Account::created(function (Account $account) {
                $this->dispatch(RefreshSubscriptions::getInstance($account));

                $channels = $account->channels;
                foreach ($channels as $channel) {
                    $this->dispatch(RefreshVideos::getInstance($channel));
                }
            });
        }

        Blade::setContentTags('<<', '>>');
        Blade::setEscapedContentTags('<<<', '>>>');
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
