<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(SitesTableSeeder::class);

        if (env('APP_ENV') === 'local') {
            $this->call(ChannelsTableSeeder::class);
            $this->call(UsersTableSeeder::class);
        }

        Model::reguard();
    }
}
