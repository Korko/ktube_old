<?php

namespace Korko\kTube\Jobs\RefreshSubscriptions;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Korko\kTube\Account;
use Korko\kTube\Jobs\Job;

class RefreshAllSubscriptions extends Job implements SelfHandling, ShouldQueue {

    use InteractsWithQueue, SerializesModels, DispatchesJobs;

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string
     */
    public $queue = 'channels';

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $accounts = Account::with('site')->get();

        foreach ($accounts as $account) {
            switch ($account->site->provider) {
                case 'google':
                    $this->dispatch(new RefreshYoutubeSubscriptions($account));
                    break;

                default:
                    Log::error('Account provider not managed', ['account' => $account]);
            }
        }
    }
}