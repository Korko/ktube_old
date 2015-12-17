<?php

namespace Korko\kTube;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $fillable = ['channel_id', 'name', 'site_id', 'scanned_at'];

    protected $dates = ['scanned_at'];

    public $timestamps = false;

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function accounts()
    {
        return $this->belongsToMany(self::class, 'account_subscriptions');
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
