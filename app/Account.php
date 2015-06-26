<?php

namespace Korko\kTube;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'accounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['site', 'name', 'access_token', 'refresh_token', 'expires_at'];

    public function user()
    {
        return $this->hasOne('Korko\kTube\User');
    }
}
