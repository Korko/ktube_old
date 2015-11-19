<?php

namespace Korko\kTube;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    protected $fillable = ['name', 'user_id'];

    public function videos()
    {
        return $this->belongsToMany(Video::class)->withTimestamps();
    }

    public function accounts()
    {
        return $this->belongsToMany(Account::class)->withPivot('playlist_site_id');
    }
}
