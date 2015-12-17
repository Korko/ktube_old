<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

function number_random($length)
{
    $pool = '0123456789';

    return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
}

$factory->define(Korko\kTube\User::class, function ($faker) {
    return [
        'name'  => $faker->name,
        'email' => $faker->email,
    ];
});

$factory->define(Korko\kTube\Account::class, function ($faker) {
    return [
        //'site_id' => $site->id,
        'account_id' => number_random(21),
        //'user_id' => $user->id,
        'name'          => $faker->name,
        'access_token'  => str_random(10),
        'refresh_token' => null,
        'expires_at'    => Carbon\Carbon::now(),
    ];
});

$factory->define(Korko\kTube\Channel::class, function ($faker) {
    return [
        'channel_id' => number_random(20),
        'name'       => $faker->name,
        //'site_id' => $sites->random()->id
    ];
});

$factory->define(Korko\kTube\Video::class, function ($faker) {
    return [
        'video_id' => number_random(20),
        'name'     => $faker->name,
        //'channel_id' => $channel->id,
        'published_at' => $faker->dateTime(),
    ];
});
