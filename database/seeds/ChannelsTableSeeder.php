<?php

use Illuminate\Database\Seeder;
use Korko\kTube\Channel;
use Korko\kTube\Site;
use Korko\kTube\Video;

class ChannelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('videos')->delete();
        DB::table('channels')->delete();

        $sites = Site::all();

        foreach ($sites as $site) {
            factory(Channel::class, 100)
                ->create(['site_id' => $site->id])
                ->each(function ($channel) {
                    factory(Video::class, rand(10, 100))
                        ->create(['channel_id' => $channel->id]);
                });
        }
    }
}