<?php

namespace Korko\kTube\Http\Controllers;

use Korko\kTube\Http\Controllers\Controller;
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
        $tokens = Token::where('user_id', '=', Auth::user()->id)->get();
        foreach ($tokens as $token) {
        }
    }
}
