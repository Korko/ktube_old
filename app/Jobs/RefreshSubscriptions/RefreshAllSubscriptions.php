<?php

namespace Korko\kTube\Jobs\RefreshSubscriptions;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\SerializesModels;
use Korko\kTube\Account;
use Korko\kTube\Exceptions\InvalidProviderException;
use Korko\kTube\Jobs\Job;
use Log;

class RefreshAllSubscriptions extends Job implements SelfHandling, ShouldQueue
{
    use SerializesModels, DispatchesJobs;

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
            try {
                $this->dispatch(new RefreshSubscriptions($account));
            } catch (InvalidProviderException $e) {
                //                Log::error($e->getMessage(), ['account' => $account]);
            }
        }
    }
}
