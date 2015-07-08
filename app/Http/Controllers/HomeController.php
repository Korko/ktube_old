<?php

namespace Korko\kTube\Http\Controllers;

use Auth;
use Korko\kTube\Http\Controllers\Controller;
use Korko\kTube\Jobs\FetchAccountSubscriptions;
use Korko\kTube\Jobs\RefreshToken;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function home()
    {
        // Should be in a specific Job
        //$accounts = Auth::user()->accounts()->with('site')->get();
        //foreach($accounts as $account) {
        //    $this->dispatch(new RefreshToken($account->site->name, $account->refresh_token));
        //    $this->dispatch(new FetchAccountSubscriptions($account));
        //}

        return view('home', [
            'channels' => Auth::user()->channels()->get()->pluck('channels')->collapse()->sort(function($channel1, $channel2) {
                return strcasecmp($channel1->name, $channel2->name);
            })
        ]);
    }
}
