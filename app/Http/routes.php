<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', ['middleware' => 'guest', function () {
    return view('welcome');
}]);

Route::get('/auth/login/{provider}', 'AuthController@getLogin');
Route::get('/auth/login/callback/{provider}', 'AuthController@postLogin');

Route::get('/video/{video}', 'VideoController@show');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/auth/logout', 'AuthController@getLogout');

    Route::get('/home', 'VideoController@index');

    Route::get('/accounts', 'AccountsController@index');
    Route::get('/profile', 'ProfileController@index');

    Route::get('/videos/all', 'VideoController@all');

    Route::get('/playlists/all', 'PlaylistController@index');
});

if (env('APP_ENV') === 'local') {
    Route::get('/auth/debug', 'AuthController@debugLogin');
}
