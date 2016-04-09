<?php

namespace Korko\kTube\Library\RefreshVideos;

use Illuminate\Support\Collection;
use Korko\kTube\Channel;
use Korko\kTube\Exceptions\InvalidProviderException;
use Korko\kTube\Video;

abstract class RefreshVideos
{
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
        $videos = $this->fetchVideos();

        // Now save those videos in DB
        $this->saveVideos($videos);

        // Update the channel with those new data
        $this->updateChannel($videos);
    }

    /**
     * Fetch new videos from this specific channel.
     *
     * @return Collection List of new videos to add to this channel
     */
    abstract protected function fetchVideos();

    /**
     * Save those videos (removes the duplicates).
     *
     * @param Collection $videos [description]
     *
     * @return void
     */
    protected function saveVideos(Collection $videos)
    {//TODO those videos are not instances of Channel but simple arrays and channel_id is not defined
        $videos
            ->chunk(100)
            ->each(function ($videos) {
                $videos = new Collection(array_diff_key(
                    $videos->keyBy('video_id')->all(),
                    Video::where('channel_id', $this->channel->id)
                        ->whereIn('video_id', $videos->pluck('video_id')->all())
                        ->get(['video_id'])->keyBy('video_id')->all()
                ));

                if (!$videos->isEmpty()) {
                    Video::insert($videos->toArray());
                }
            });
    }

    /**
     * Update the related channel with the max published date from those videos.
     *
     * @param Collection $videos [description]
     *
     * @return void
     */
    protected function updateChannel(Collection $videos)
    {
        // Find the max publication date to limit for next time
        // There can be lag so don't take now as the limit
        $maxPublishedDate = null;
        foreach ($videos as $video) {
            $maxPublishedDate = $maxPublishedDate ? $maxPublishedDate->max($video->published_at) : $video->published_at;
        }

        // Update the channel for next loop
        $this->channel->update(['scanned_at' => $maxPublishedDate]);
    }
}
