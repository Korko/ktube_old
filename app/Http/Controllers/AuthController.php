<?php

namespace Korko\kTube\Http\Controllers;

use Auth;
use Carbon\Carbon;
use Korko\kTube\Account;
use Korko\kTube\Site;
use Korko\kTube\User;
use Socialite;

class AuthController extends Controller
{
    public function __construct()
    {
        // routes to logIn are not limited to guests
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
            $account = $this->updateOrCreateAccount($provider, $userData);

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

    protected function updateOrCreateAccount($provider, $userData)
    {
        $site = Site::where('provider', $provider)->firstOrFail();

        $account = Account::firstOrNew([
            'site_id'    => $site->id,
            'account_id' => $userData->id,
        ]);

        $user = $this->getAuthUser($account, $userData);

        $account->fill([
            'user_id'       => $user->id,
            'name'          => $userData->name ?: $userData->nickname,
            'access_token'  => $userData->token,
            'refresh_token' => $userData->refreshToken,
            'expires_at'    => Carbon::now()->addSeconds($userData->tokenExpiresIn),
        ])->save();

        return $account;
    }

    protected function getAuthUser($account, $userData)
    {
        // If nobody is connected
        if (Auth::user() === null) {
            $user = $this->findOrCreateAccountUser($account, $userData);

            Auth::login($user, true);
        }

        return Auth::user();
    }

    protected function findOrCreateAccountUser($account, $userData)
    {
        // Then tries to get the user from the account
        if (!isset($account->user_id)) {
            // If it's a new account, then create a new user
            $user = User::create([
                'name'  => $userData->name ?: $userData->nickname,
                'email' => $userData->email,
            ]);
        } else {
            $user = $account->user()->first();
        }

        return $user;
    }

    public function debugLogin()
    {
        $user = User::firstOrFail();

        Auth::login($user, true);

        return redirect('/home')->with('message', 'Welcome, '.$user->name);
    }
}
