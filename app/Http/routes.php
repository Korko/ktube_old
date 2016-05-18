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

Route::get('/', 'HomeController@index');

Route::get('/auth/login/{provider}', 'AuthController@getLogin');
Route::get('/auth/login/callback/{provider}', 'AuthController@postLogin');

Route::get('/video/{video}', 'VideoController@show');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/auth/logout', 'AuthController@getLogout');
    Route::get('/videos/all', 'VideoController@all');
});
