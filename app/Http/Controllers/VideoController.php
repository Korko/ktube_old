<?php

namespace Korko\kTube\Http\Controllers;

use Auth;
use Hashids;
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
     * Get a list of videos
     * @param  Request $request [description]
     * @return array            Array with 2 keys: data (list of videos) and has_more (is there any video after the last one)
     */
    public function all(Request $request)
    {
        $videos = $this->getVideosFromRequest($request);

        foreach ($videos as &$video) {
            $video->hash = Hashids::encode($video->id);
            unset($video->id);
        }

        return [
            'data'     => $videos->slice(0, 20),
            'has_more' => isset($videos[20]), // If the 21's exists, there's more
        ];
    }

    /**
     * Get a list of Videos (DB instances) depending on Request conditions
     * @param  Request $request [description]
     * @return array            List of Video (DB instance)
     */
    private function getVideosFromRequest(Request $request)
    {
        // We can specifically ask for videos after a last one or before a first one
        if (!empty($request->has('last'))) {

            $video  = Video::byHash($request->get('last'));
            $videos = $this->getVideosBefore($video);

        // We can specifically ask for videos after a last one or before a first one
        } else if (!empty($request->has('first'))) {

            $video  = Video::byHash($request->get('first'));
            $videos = $this->getVideosAfter($video);

        } else {

            $videos = $this->getVideos();

        }

        return $videos;
    }

    /**
     * Get an Eloquent instance about Video for the connected user
     * @return Eloquent\Request [description]
     */
    private function getVideosRequest()
    {
        $channels = Auth::user()->accounts()
            ->select('id')
            ->with('channels')->get()
            ->pluck('channels')->collapse();

        return Video::whereIn('channel_id', $channels->pluck('id')->all())
            ->select(['id', 'name', 'published_at', 'thumbnail', 'channel_id'])
            ->with('channel.site')
            ->orderBy('published_at', 'desc')
            ->limit(21);
    }

    /**
     * Get a bunch of videos for the connected user
     * @return array [description]
     */
    private function getVideos()
    {
        return $this->getVideosRequest()->get();
    }

    /**
     * Get a bunch of videos for the connected user published before an other one
     * @return array [description]
     */
    private function getVideosBefore(Video $video)
    {
        return $this->getVideosRequest()
            ->where('id', '<', $video->id)
            ->where('published_at', '<', $video->published_at)
            ->get();
    }

    /**
     * Get a bunch of videos for the connected user published after an other one
     * @return array [description]
     */
    private function getVideosAfter(Video $video)
    {
        return $this->getVideosRequest()
            ->where('id', '>', $video->id)
            ->where('published_at', '>', $video->published_at)
            ->get();
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
