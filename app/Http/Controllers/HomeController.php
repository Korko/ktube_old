<?php

namespace Korko\kTube\Http\Controllers;

use Auth;
use JavaScript;
use Korko\kTube\Video;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $videos = Video::byUser(Auth::user())->page();

            JavaScript::put([
                'videos'   => $videos->slice(0, 20),
                'hasMore' => isset($videos[20]), // If the 21's exists, there's more
            ]);

            return view('welcome.user');
        } else {
            return view('welcome.guest');
        }
    }
}
