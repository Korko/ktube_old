<?php

namespace Korko\kTube\Console\Commands;

use DateTime;
use Illuminate\Console\Command;
use Korko\kTube\Account;
use Korko\kTube\Library\BackupAccount\BackupAccount as BackupAccountLibrary;

class BackupAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:account {account} {since=yesterday}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup an account and save all videos since a specific date in a new playlist.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $accountId = $this->argument('account');

        $account = Account::findOrFail($accountId);

        $backupDate = new DateTime($this->argument('since'));

        BackupAccountLibrary::getInstance($account, $backupDate)->handle();
    }
}
