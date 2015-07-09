<?php

namespace Korko\kTube;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = ['video_id', 'name', 'channel_id', 'published_at'];

    protected $dates = ['published_at'];

    public $timestamps = false;

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }
}
