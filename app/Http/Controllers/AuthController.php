<?php

namespace Korko\kTube\Http\Controllers;

use Korko\kTube\Http\Controllers\Controller;

class AuthController extends Controller
{
    protected $socialite;

    public function __construct(Socialite $socialite){
        $this->socialite = $socialite;
    }

    public function getLogin($provider)
    {
        if (!config("services.$provider")) abort('404');
        return $this->socialite->with($provider)->redirect();
    }

    public function postLogin($provider)
    {
        if ($user = $this->socialite->with($provider)->user()) {
            dd($user);
        } else {
            abort(500);
        }
    }

    public function getLogout()
    {
        Auth::logout();
        return redirect('/');
    }
}
