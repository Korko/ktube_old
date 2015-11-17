<?php

namespace Korko\kTube\Jobs\RefreshPlaylists;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Korko\kTube\Account;
use Korko\kTube\Exceptions\InvalidProviderException;
use Korko\kTube\Jobs\Job;
use Log;

class RefreshAllPlaylists extends Job implements SelfHandling, ShouldQueue
{
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
        $accounts = Account::all();

        // For each of these accounts, get the playlists
        foreach ($accounts as $account) {
            try {
                $this->dispatch(RefreshPlaylists::getInstance($account));
            } catch(InvalidProviderException $e) {
//                Log::error($e->getMessage(), ['account' => $account]);
            }
        }
    }
}
