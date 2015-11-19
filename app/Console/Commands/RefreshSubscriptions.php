<?php

namespace Korko\kTube\Console\Commands;

use Illuminate\Console\Command;
use Korko\kTube\Account;
use Korko\kTube\Library\RefreshSubscriptions\RefreshSubscriptions as RefreshSubscriptionsLibrary;
use Korko\kTube\Library\RefreshChannelsVideos\RefreshChannelsVideos as RefreshChannelsVideosLibrary;

class RefreshSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh:subscriptions {account}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh an account\'s subscriptions.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $accountId = $this->argument('account');

        $account = Account::findOrFail($accountId);

        $channels = RefreshSubscriptionsLibrary::getInstance($account)->handle();

        foreach ($channels as $channel) {
            RefreshChannelsVideosLibrary::getInstance($channel)->handle();
        }
    }
}
