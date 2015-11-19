<?php

namespace Korko\kTube\Jobs\RefreshVideos;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Korko\kTube\Channel;
use Korko\kTube\Exceptions\InvalidProviderException;
use Korko\kTube\Jobs\Job;
use Korko\kTube\Video;

abstract class RefreshVideos extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string
     */
    public $queue = 'videos';

    public static function getInstance(Channel $channel)
    {
        switch ($channel->site->provider) {
                case 'google':
                    return new RefreshYoutubeVideos($channel);
                    break;

                case 'dailymotion':
                    return new RefreshDailymotionVideos($channel);
                    break;

                default:
                    throw new InvalidProviderException('Channel provider not managed');
            }
    }

    protected $channel;

    public function __construct(Channel $channel)
    {
        $this->channel = $channel;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $videos = $this->fetchVideos($this->channel);

        // Now save those videos in DB
        $this->saveVideos($this->channel, $videos);

        // Update the channel with those new data
        $this->updateChannel($this->channel, $videos);
    }

    /**
     * Fetch new videos from this specific channel
     * @param  Channel $channel [description]
     * @return Collection       List of new videos to add to this channel
     */
    abstract protected function fetchVideos(Channel $channel);

    /**
     * Save those videos (removes the duplicates)
     * @param  Channel    $channel [description]
     * @param  Collection $videos  [description]
     * @return [type]              [description]
     */
    protected function saveVideos(Channel $channel, Collection $videos)
    {
        $videos
            ->chunk(100)
            ->each(function ($videos) use ($channel) {
                $videos = new Collection(array_diff_key(
                    $videos->keyBy('video_id')->all(),
                    Video::where('channel_id', $channel->id)
                        ->whereIn('video_id', $videos->pluck('video_id')->all())
                        ->get(['video_id'])->keyBy('video_id')->all()
                ));

                if (!$videos->isEmpty()) {
                    Video::insert($videos->toArray());
                }
            });
    }

    /**
     * Update the related channel with the max published date from those videos
     * @param  Channel    $channel [description]
     * @param  Collection $videos  [description]
     * @return [type]              [description]
     */
    protected function updateChannel(Channel $channel, Collection $videos)
    {
        // Find the max publication date to limit for next time
        // There can be lag so don't take now as the limit
        $maxPublishedDate = null;
        foreach ($videos as $video) {
            $maxPublishedDate = $maxPublishedDate ? $maxPublishedDate->max($video->published_at) : $video->published_at;
        }

        // Update the channel for next loop
        $channel->update(['scanned_at' => $maxPublishedDate]);
    }
}
