<?php

namespace Korko\kTube\Library\RefreshSubscriptions;

use Korko\kTube\Account;
use Korko\kTube\Library\YoutubeApiConnected;

class RefreshYoutubeSubscriptions extends RefreshSubscriptions
{
	private $yt;

	public function __construct(Account $account)
	{
		$this->yt = new YoutubeApiConnected($account);

		parent::__construct($account);
	}

    protected function getChannels()
    {
        return $this->yt->getMySubscriptions();
    }
}
