<?php

namespace Korko\kTube\Http\Controllers;

use Auth;
use Korko\kTube\Channel;
use Korko\kTube\Http\Controllers\Controller;
use Korko\kTube\Video;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function home()
    {
        $channels = Auth::user()
            ->accounts()->with('channels')->get()
            ->pluck('channels')->collapse();

        $videos = Video::whereIn('channel_id', $channels->pluck('id')->all())
            ->orderBy('published_at', 'desc')
            ->simplePaginate(20);

        return view('home', [
            'videos' => $videos
        ]);
    }
}
