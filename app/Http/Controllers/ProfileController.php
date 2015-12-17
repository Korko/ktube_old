<?php

namespace Korko\kTube\Http\Controllers;

use Auth;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $accounts = Auth::user()->accounts()->with('site')->get();

        return view('profile', [
            'accounts' => $accounts,
        ]);
    }
}
