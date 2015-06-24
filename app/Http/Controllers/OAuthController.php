<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class OAuthController extends Controller
{
    public function auth(Request $request, $driver)
    {

    }

    /**
     * Redirect the user to the driver's authentication page.
     *
     * @return Response
     */
    public function redirectToProvider($driver)
    {
        return Socialite::driver($driver)
            ->scopes(['https://www.googleapis.com/auth/youtube.force-ssl'])
            ->redirect();
    }

    /**
     * Obtain the user information from the driver.
     *
     * @return Response
     */
    public function handleProviderCallback($driver)
    {
        $user = Socialite::driver($driver)->user();

        // $user->token;
    }
}