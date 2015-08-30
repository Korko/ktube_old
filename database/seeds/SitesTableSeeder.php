<?php

use Illuminate\Database\Seeder;
use Korko\kTube\Site;

class SitesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sites')->delete();
        Site::insert([
            ['name' => 'Youtube', 'provider' => 'google'],
            ['name' => 'Dailymotion', 'provider' => 'dailymotion'],
            ['name' => 'Vimeo', 'provider' => 'vimeo']
        ]);
    }
}