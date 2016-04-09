<?php

namespace Korko\kTube\Http\Controllers;

use Hashids;
use Illuminate\Http\Request;
use Korko\kTube\Playlist;

class PlaylistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        dd(Playlist::with('videos')->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $playlist = Playlist::findOrFail($id);

        return view('playlists.show', ['url' => '/playlists/all/'.$id, 'playlist' => $playlist]);
    }

    public function all(Request $request, $id)
    {
        $playlist = Playlist::findOrFail($id);

        $req = $playlist->videos()
            ->select(['id', 'name', 'published_at', 'thumbnail', 'channel_id'])
            ->with('channel.site')
            ->orderBy('published_at', 'desc')
            ->limit(21);

        if (!empty($request->has('last'))) {
            $last = Hashids::decode($request->get('last'));
            $last = array_pop($last);

            if ($last !== null) {
                $req->where('id', '<', $last);
            }
        }

        $videos = $req->get();

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
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
