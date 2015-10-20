<?php

namespace Korko\kTube\Http\Controllers;

use Auth;
use Hashids;
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

    public function all()
    {
        $channels = Auth::user()->accounts()
            ->select('id')
            ->with('channels')->get()
            ->pluck('channels')->collapse();

        $videos = Video::whereIn('channel_id', $channels->pluck('id')->all())
            ->select(['id', 'name', 'published_at', 'thumbnail', 'channel_id'])
            ->with('channel.site')
            ->orderBy('published_at', 'desc')
            ->simplePaginate(20);

        foreach($videos as &$video) {
            $video->hash = Hashids::encode($video->id);
        }

        return [
            'data' => $videos->items(),
            'per_page' => $videos->perPage(),
            'current_page' => $videos->currentPage(),
            'has_more' => $videos->hasMorePages()
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
