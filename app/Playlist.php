<?php

namespace Korko\kTube;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    protected $fillable = ['playlist_id', 'name', 'account_id', 'type'];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function videos()
    {
        return $this->belongsToMany(Video::class)->withTimestamps();
    }
}
