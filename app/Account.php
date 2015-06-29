<?php

namespace Korko\kTube;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Korko\kTube\User;

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

    public static function findByProviderOrCreate($provider, $userData)
    {
        $account = Account::where('provider', '=', $provider)
            ->where('provider_id', '=', $userData->id)
            ->first();

        if (!$account) {
            if (! ($user = Auth::user())) {
                $user = User::create([
                    'name' => $userData->name ?: $userData->nickname,
                    'email' => $userData->email
                ]);
            }

            $account = Account::create([
                'user_id' => $user->id,
                'provider' => $provider,
                'provider_id' => $userData->id,
                'name' => $userData->name ?: $userData->nickname,
                'access_token' => $userData->token
            ]);
        }

        return $account;
    }
}
