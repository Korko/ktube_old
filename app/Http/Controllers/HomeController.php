<?php

namespace Korko\kTube\Http\Controllers;

use Auth;
use JavaScript;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            JavaScript::put(['videos' => Video::byUser(Auth::user())->page()]);

            return view('welcome.user');
        } else {
            return view('welcome.guest');
        }
    }
}
