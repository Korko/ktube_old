<?php

namespace Korko\kTube\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
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
        return view('videos.index', ['url' => '/videos/all']);
    }

    /**
     * Get a list of videos.
     *
     * @param Request $request [description]
     *
     * @return array Array with 2 keys: data (list of videos) and has_more (is there any video after the last one)
     */
    public function all(Request $request)
    {
        $videos = $this->getVideosFromRequest($request);

        return [
            'videos'  => $videos->slice(0, 20),
            'hasMore' => isset($videos[20]), // If the 21's exists, there's more
        ];
    }

    /**
     * Get a list of Videos (DB instances) depending on Request conditions.
     *
     * @param Request $request [description]
     *
     * @return array List of Video (DB instance)
     */
    private function getVideosFromRequest(Request $request)
    {
        // We can specifically ask for videos after a last one or before a first one
        if (!empty($request->has('last'))) {
            $video = Video::byHash($request->get('last'));
            $model = Video::before($video);

        // We can specifically ask for videos after a last one or before a first one
        } elseif (!empty($request->has('first'))) {
            $video = Video::byHash($request->get('first'));
            $model = Video::after($video);
        }

        return $model->byUser(Auth::user())->page();
    }

    /**
     * Display the specified resource.
     *
     * @param Video $video
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Video $video)
    {
        return view('videos.show', [
            'video' => $video,
        ]);
    }
}
