<?php

namespace Korko\kTube\Jobs\BackupAccount;

use Korko\kTube\Account;
use Korko\kTube\Library\YoutubeApiConnected;

class BackupYoutubeAccount extends BackupAccount
{
    use YoutubeJob;

    protected function createPlaylist(Account $account, $title, $videos)
    {
        $yt = new YoutubeApiConnected($account);

        return $yt->addPlaylist($title, $videos);
    }
}
