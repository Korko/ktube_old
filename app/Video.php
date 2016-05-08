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
}
