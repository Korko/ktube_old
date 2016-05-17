<?php

namespace Korko\kTube\Http\Controllers;

use Auth;

class HomeController extends Controller
{
    public function index()
    {
        if(Auth::check()) {
            return view('welcome.user');
        } else {
            return view('welcome.guest');
        }
    }
}
