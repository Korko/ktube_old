<?php

namespace Korko\kTube\Http\Controllers;

use Illuminate\Http\Request;

use Korko\kTube\Http\Requests;
use Korko\kTube\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function home()
    {
        return view('dashboard');
    }
}
