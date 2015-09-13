<?php

namespace Korko\kTube\Jobs\BackupAccount;

use DateTime;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Korko\kTube\Jobs\Job;
use Korko\kTube\User;

class BackupAllAccounts extends Job implements SelfHandling, ShouldQueue {

    use InteractsWithQueue, SerializesModels, DispatchesJobs;

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string
     */
    public $queue = 'playlists';

    protected $backupDate;

    public function __construct(DateTime $backupDate)
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
            switch ($account->site->provider) {
                case 'google':
                    $this->dispatch(new BackupYoutubeAccount($account, $this->backupDate));
                    break;

                default:
                    Log::error('Account provider not managed', ['account' => $account]);
            }
        }
    }
}
