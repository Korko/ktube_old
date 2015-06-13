<?php

namespace Korko\kTube\Http\Controllers\OAuth;

use Korko\kTube\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Korko\kTube\Lib\Youtube as Lib;
use Korko\kTube\Token;
use Carbon\Carbon;
use Auth;

class Youtube extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function init()
    {
        return redirect(Lib::getAuthUrl());
    }

    public function auth(Request $request)
    {
        $this->validate($request, [
            'code' => 'required'
        ]);

        try {
            $data = Lib::validateCode($request->get('code'));
        } catch(Exceptione) {
            abort(500);
        }

        $token = Token::firstOrNew(['user_id' => Auth::user()->id, 'type' => 'youtube']);
        $token->fill([
            'access_token' => $data->access_token,
            'refresh_token' => $data->refresh_token,
            'expires_at' => Carbon::now()->addSeconds($data->expires_in)
        ]);
        $token->save();

        return redirect('/home');
    }
}
