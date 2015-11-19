<?php

namespace Korko\kTube;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['user_id', 'site_id', 'account_id', 'name', 'access_token', 'refresh_token', 'expires_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function channels()
    {
        return $this->belongsToMany(Channel::class, 'account_subscriptions');
    }

    public function playlists()
    {
        return $this->belongsToMany(Playlist::class)->withPivot('playlist_site_id');
    }

    public function scopeCanRefreshTokens($query)
    {
        return $query->whereHas('site', function ($query) {
            return $query->whereNotIn('provider', ['vimeo']);
        });
    }
}
