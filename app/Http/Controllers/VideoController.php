<?php

namespace Korko\kTube\Http\Controllers;

use Auth;
use Hashids;
use Illuminate\Http\Request;
use Korko\kTube\Account;
use Korko\kTube\Http\Controllers\Controller;;
use Korko\kTube\Video;

class VideoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('videos.index');
    }

    public function all(Request $request)
    {
        $channels = Auth::user()->accounts()
            ->select('id')
            ->with('channels')->get()
            ->pluck('channels')->collapse();

        $req = Video::whereIn('channel_id', $channels->pluck('id')->all())
            ->select(['id', 'name', 'published_at', 'thumbnail', 'channel_id'])
            ->with('channel.site')
            ->orderBy('published_at', 'desc')
            ->limit(21);

        if(!empty($request->has('last'))) {

            $last = Hashids::decode($request->get('last'));
            $last = array_pop($last);

            if($last !== NULL) {
                $req->where('id', '<', $last);
            }
        }

        $videos = $req->get();

        foreach($videos as &$video) {
            $video->hash = Hashids::encode($video->id);
            unset($video->id);
        }

        return [
            'data' => $videos->slice(0, 20),
            'has_more' => isset($videos[20]) // If the 21's exists, there's more
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  Video  $video
     * @return \Illuminate\Http\Response
     */
    public function show(Video $video)
    {
        return view('videos.show', [
            'video' => $video
        ]);
    }
}
