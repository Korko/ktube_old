<?php

namespace Korko\kTube\Library\BackupAccount;

use DateTime;
use Korko\kTube\Account;
use Korko\kTube\Library\YoutubeApiConnected;

class BackupYoutubeAccount extends BackupAccount
{
    private $yt;

    public function __construct(Account $account, DateTime $backupDate)
    {
        $this->yt = new YoutubeApiConnected($account);

        parent::__construct($account, $backupDate);
    }

    protected function createPlaylist($title, $videos)
    {
        return $this->yt->addPlaylist($title, $videos);
    }
}
