<?php

namespace Korko\kTube;

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
}
