<?php

namespace Korko\kTube\Jobs\BackupAccount;

use DateTime;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\SerializesModels;
use Korko\kTube\Exceptions\InvalidProviderException;
use Korko\kTube\Jobs\Job;
use Korko\kTube\User;
use Log;

class BackupAllAccounts extends Job implements SelfHandling, ShouldQueue
{
    use SerializesModels, DispatchesJobs;

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string
     */
    public $queue = 'playlists';

    protected $backupDate;

    public function __construct(DateTime $backupDate = null)
    {
        $this->backupDate = $backupDate ?: new DateTime('yesterday');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $accounts = User::where('holidays', true)->with('accounts.site')->get(['id'])
            ->pluck('accounts')->collapse();

        foreach ($accounts as $account) {
            try {
                $this->dispatch(BackupAccount::getInstance($account, $this->backupDate));
            } catch(InvalidProviderException $e) {
//                Log::error($e->getMessage(), ['account' => $account]);
            }
        }
    }
}
