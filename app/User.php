<?php

namespace Korko\kTube;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    protected $fillable = ['name', 'email', 'holidays'];

    protected $hidden = ['remember_token'];

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function channels()
    {
        return $this->accounts()->with('channels');
    }
}
