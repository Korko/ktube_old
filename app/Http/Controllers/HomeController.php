<?php

namespace Korko\kTube\Http\Controllers;

use Korko\kTube\Http\Controllers\Controller;
use Korko\kTube\Libs\Youtube;
use Illuminate\Http\Request;
use Korko\kTube\Token;
use Carbon\Carbon;
use Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function home()
    {
        \Korko\kTube\Libs\TokenManager::refreshAll();
        $activities = [];
        $tokens = Token::where('user_id', '=', Auth::user()->id)->get();
        foreach ($tokens as $token) {
            switch ($token->type) {
                case 'youtube':
                    $activities['youtube'] = Youtube::getActivities($token);
                    break;
            }
        }
        dd($activities);
    }
}
