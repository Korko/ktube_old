<?php

namespace Korko\kTube\Jobs\BackupAccount;

use DateTime;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Korko\kTube\Account;
use Korko\kTube\Jobs\Job;
use Korko\kTube\Library\BackupAccount\BackupAccount as BackupAccountLibrary;

abstract class BackupAccount extends Job implements SelfHandling, ShouldQueue
{
    use SerializesModels;

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string
     */
    public $queue = 'playlists';

    public function __construct(Account $account, DateTime $backupDate)
    {
        $this->account = $account;

        $this->backupDate = $backupDate;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        BackupAccountLibrary::getInstance($this->account, $this->backupDate)->handle();
    }
}
