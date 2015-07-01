<?php

namespace Korko\kTube\Http\Controllers;

use Korko\kTube\Http\Controllers\Controller;
use Korko\kTube\Account;
use Korko\kTube\User;
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
            $socialite
                ->asOffline()
                ->addScope('https://www.googleapis.com/auth/youtube.force-ssl');
        }

        return $socialite->redirect();
    }

    public function postLogin($provider)
    {
        if ($userData = Socialite::with($provider)->user()) {
            $this->createAndAuthUser($userData);

            $account = Account::updateOrCreateByUserData($provider, $userData);

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

    protected function createAndAuthUser($userData)
    {
        if (! ($user = Auth::user())) {
            $user = User::create([
                'name' => $userData->name ?: $userData->nickname,
                'email' => $userData->email
            ]);

            Auth::login($user, true);
        }
    }
}
