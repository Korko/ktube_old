<?php

namespace Korko\kTube;

use Illuminate\Database\Eloquent\Model;

class YoutubeChannel extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'youtube_channels';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'title', 'description'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function videos()
    {
        return $this->hasMany('Korko\kTube\YoutubeVideo', 'youtube_channel_videos');
    }
}
