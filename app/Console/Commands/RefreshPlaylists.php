<?php

namespace Korko\kTube\Console\Commands;

use Illuminate\Console\Command;
use Korko\kTube\Account;
use Korko\kTube\Library\RefreshPlaylists\RefreshPlaylists as RefreshPlaylistsLibrary;

class RefreshPlaylists extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh:playlists {account}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh an account\'s playlists.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $accountId = $this->argument('account');

        $account = Account::findOrFail($accountId);

        RefreshPlaylistsLibrary::getInstance($account)->handle();
    }
}
