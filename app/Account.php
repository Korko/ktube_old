<?php

namespace Korko\kTube;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Korko\kTube\User;
use Carbon\Carbon;

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
    protected $fillable = ['user_id', 'provider', 'provider_id', 'name', 'access_token', 'refresh_token', 'expires_at'];

    public function user()
    {
        return $this->belongsTo('Korko\kTube\User');
    }

    public static function updateOrCreateByUserData($provider, $userData)
    {
        return Account::updateOrCreate([
            'user' => Auth::user()->id,
            'provider' => $provider,
            'provider_id' => $userData->id
        ], [
            'name' => $userData->name ?: $userData->nickname,
            'access_token' => $userData->token,
            'refresh_token' => $userData->refreshToken,
            'expires_at' => Carbon::now()->addSeconds($userData->tokenExpiresIn)
        ]);
    }
}
