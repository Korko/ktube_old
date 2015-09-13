<?php

namespace Korko\kTube\Jobs\BackupAccount;

use Carbon\Carbon;
use DateTime;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Korko\kTube\Account;
use Korko\kTube\Jobs\Job;
use Korko\kTube\Video;

abstract class BackupAccount extends Job implements SelfHandling, ShouldQueue {

    use InteractsWithQueue, SerializesModels;

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string
     */
    public $queue = 'playlists';

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

        $this->createPlaylist($this->account, 'Backup '.$this->backupDate->format('Y-m-d'), $videos);
    }

    /**
     * Get videos published yesterday
     * @param  Collection $channels List of channels from which get videos
     * @return Collection           List of videos of these channels published yesterday
     */
    protected function getVideos(Collection $channels)
    {
        return Video::whereIn('channel_id', $channels->pluck('id')->all())
            ->whereRaw('published_at BETWEEN DATE_SUB(?, INTERVAL 1 DAY) AND ?', [$this->backupDate, $this->backupDate])
            ->orderBy('published_at', 'asc')
            ->get();
    }

    abstract protected function createPlaylist(Account $account, $title, $videos);
}
