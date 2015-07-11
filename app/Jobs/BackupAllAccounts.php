<?php

namespace Korko\kTube\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Korko\kTube\Account;
use Korko\kTube\User;

class BackupAllAccounts extends Job implements SelfHandling, ShouldQueue {

    use InteractsWithQueue, SerializesModels, DispatchesJobs;

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string
     */
    public $queue = 'playlists';

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
            $this->dispatch(new BackupAccount($account));
        }
    }
}