<?php

namespace Korko\kTube;

use Hashids;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = ['video_id', 'name', 'channel_id', 'published_at', 'thumbnail'];

    protected $dates = ['published_at'];

    public $timestamps = false;

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function scopeFromSite($query, $provider)
    {
        return $query->whereHas('channel', function ($query) use ($provider) {
            return $query->whereHas('site', function ($query) use ($provider) {
                return $query->where('provider', $provider);
            });
        });
    }

    public function scopeByHash($query, $hash)
    {
        $ids = Hashids::decode($hash);
        $id = array_pop($ids);

        return $query->find($id);
    }

    public function scopeByUser($query, User $user)
    {
        $channels = $user->accounts()
            ->select('id')
            ->with('channels')->get()
            ->pluck('channels')->collapse();

        return $query->whereIn('channel_id', $channels->pluck('id')->all());
    }

    public function scopeBefore($query, Video $video)
    {
        return $query
            ->where('id', '<>', $video->id)
            ->where('published_at', '<=', $video->published_at);
    }

    public function scopeAfter($query, Video $video)
    {
        return $query
            ->where('id', '<>', $video->id)
            ->where('published_at', '>=', $video->published_at);
    }

    public function scopePage($query)
    {
        $videos = $query
            ->select(['id', 'name', 'published_at', 'thumbnail', 'channel_id'])
            ->with('channel.site')
            ->orderBy('published_at', 'desc')
            ->limit(21)
            ->get();

        foreach ($videos as &$video) {
            $video->hash = Hashids::encode($video->id);
            unset($video->id);
        }

        return $videos;
    }
}
