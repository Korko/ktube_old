<?php

namespace Korko\kTube\Library\BackupAccount;

use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Korko\kTube\Account;
use Korko\kTube\Exceptions\InvalidProviderException;
use Korko\kTube\Video;

abstract class BackupAccount
{
    public static function getInstance(Account $account, DateTime $backupDate)
    {
        switch ($account->site->provider) {
            case 'google':
                return new BackupYoutubeAccount($account, $backupDate);
                break;

            default:
                throw new InvalidProviderException('Account provider not managed');
        }
    }

    protected $account;

    protected $backupDate;

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
        $channels = $this->account
            ->with('channels')->get()
            ->pluck('channels')->collapse();

        $videos = $this->getVideos($channels);

        $this->createPlaylist('Backup '.$this->backupDate->format('Y-m-d'), $videos);
    }

    /**
     * Get videos published yesterday.
     *
     * @param Collection $channels List of channels from which get videos
     *
     * @return Collection List of videos of these channels published yesterday
     */
    protected function getVideos(Collection $channels)
    {
        return Video::whereIn('channel_id', $channels->pluck('id')->all())
            ->whereRaw('published_at BETWEEN DATE_SUB(?, INTERVAL 1 DAY) AND ?', [$this->backupDate, $this->backupDate])
            ->orderBy('published_at', 'asc')
            ->get();
    }

    abstract protected function createPlaylist($title, $videos);
}
