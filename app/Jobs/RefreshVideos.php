<?php

namespace Korko\kTube\Jobs;

use Carbon\Carbon;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Korko\kTube\Channel;
use Korko\kTube\Video;

class RefreshVideos extends Job implements SelfHandling, ShouldQueue {

    use InteractsWithQueue, SerializesModels;

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string
     */
    public $queue = 'videos';

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

    protected function fetchVideos(Channel $channel)
    {
        $videos = new Collection();

        $api = $this->getYoutubeApi();

        // TODO: Provider should not only be Youtube
        // There might be multiple pages to request so make a loop untile its done
        $pageToken = null;
        do {
            $items = $api->search->listSearch('snippet', [
                'channelId'      => $channel->channel_id,
                'order'          => 'date',
                'safeSearch'     => 'none',
                'type'           => 'video',
                'publishedAfter' => $channel->scanned_at->setTimezone('UTC')->toRfc3339String(),
                'maxResults'     => 50,
                'pageToken'      => $pageToken
            ]);

            foreach ($items as $item) {
                $videos[] = $this->handleVideoData($channel, $item);
            }

            $pageToken = $items->nextPageToken;
        } while ($pageToken !== null);

        return $videos;
    }

    protected function handleVideoData(Channel $channel, $item)
    {
        return new Video([
            'channel_id'   => $channel->id,
            'video_id'     => $item->id->videoId,
            'name'         => $item->snippet->title,
            'published_at' => Carbon::parse($item->snippet->publishedAt)->setTimezone(date_default_timezone_get())
        ]);
    }

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