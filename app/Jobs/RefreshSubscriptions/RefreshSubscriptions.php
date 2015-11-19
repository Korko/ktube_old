<?php

namespace Korko\kTube\Jobs\RefreshSubscriptions;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Korko\kTube\Account;
use Korko\kTube\Jobs\Job;
use Korko\kTube\Library\RefreshSubscriptions\RefreshSubscriptions as RefreshSubscriptionsLibrary;

class RefreshSubscriptions extends Job implements SelfHandling, ShouldQueue
{
    use SerializesModels;

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string
     */
    public $queue = 'channels';

    protected $account;

    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        RefreshSubscriptionsLibrary::getInstance($this->account)->handle();
    }
}
