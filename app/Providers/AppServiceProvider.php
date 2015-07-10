<?php

namespace Korko\kTube\Providers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\ServiceProvider;
use Korko\kTube\Account;
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
        Account::created(function (Account $account) {
            $this->dispatch(new RefreshSubscriptions($account));

            $channels = Channel::where('account', $account->id)->get();
            foreach ($channels as $channel) {
                $this->dispatch(new FetchLastVideos($channel));
            }
        });
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
