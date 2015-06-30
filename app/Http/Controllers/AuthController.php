<?php

namespace Korko\kTube\Http\Controllers;

use Korko\kTube\Http\Controllers\Controller;
use Korko\kTube\Account;
use Auth;
use Socialite;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
        $this->middleware('auth', ['only' => 'getLogout']);
    }

    public function getLogin($provider)
    {
        if (!config("services.$provider")) {
            abort('404');
        }

        $socialite = Socialite::with($provider);
        if ($provider === 'google') {
            $socialite->asOffline()->scopes([
                'https://www.googleapis.com/auth/youtube.force-ssl',
                'https://www.googleapis.com/auth/youtube.readonly'
            ]);
        }
        return $socialite->redirect();
    }

    public function postLogin($provider)
    {
        if ($userData = Socialite::with($provider)->user()) {
            $account = Account::findByProviderOrCreate($provider, $userData);
            Auth::login($account->user, true);
            return redirect('/home')->with('message', 'Welcome, '.$account->user->name);
        } else {
            abort(500);
        }
    }

    public function getLogout()
    {
        $user = Auth::user();
        Auth::logout();
        return redirect('/')->with('message', 'Goodbye, '.$user->name);
    }
}
