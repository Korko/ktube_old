<?php

namespace Korko\kTube\Http\Controllers\OAuth;

use Korko\kTube\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        return redirect('https://accounts.google.com/o/oauth2/auth?'.http_build_query([
            'client_id' => config('youtube.client_id'),
            'redirect_uri' => url('oauth/youtube/auth'),
            'response_type' => 'code',
            'scope' => 'https://www.googleapis.com/auth/youtube.force-ssl',
            'approval_prompt' => 'force',
            'access_type' => 'offline',
            'state' => null,
            'login_hint' => null
        ]));
    }

    public function auth(Request $request)
    {
        $this->validate($request, [
                'code' => 'required'
            ]);

        $s = curl_init('https://accounts.google.com/o/oauth2/token');
        curl_setopt($s, CURLOPT_POST, true);
        curl_setopt($s, CURLOPT_POSTFIELDS, [
            'code' => $request->get('code'),
            'client_id' => config('youtube.client_id'),
            'client_secret' => config('youtube.client_secret'),
            'redirect_uri' => url('oauth/youtube/auth'),
            'grant_type' => 'authorization_code'
        ]);
        curl_setopt($s, CURLOPT_SSL_VERIFYPEER, 2);
        curl_setopt($s, CURLOPT_CAINFO, base_path().'/resources/cacert.pem');
        curl_setopt($s, CURLOPT_RETURNTRANSFER, true);
        $data = json_decode(curl_exec($s));

        if (curl_errno($s) || is_null($data) || isset($data->error)) {
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
