<?php

use Illuminate\Database\Seeder;
use Korko\kTube\Account;
use Korko\kTube\Channel;
use Korko\kTube\Site;
use Korko\kTube\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('accounts')->delete();
        DB::table('users')->delete();

        $sites = Site::all();

        factory(User::class, 2)
           ->create()
           ->each(function ($user) use ($sites) {
                foreach ($sites->random(rand(2, $sites->count())) as $site) {
                    $account = factory(Account::class)->create([
                        'site_id' => $site->id,
                        'user_id' => $user->id,
                    ]);

                    $channels = Channel::orderBy(DB::raw('RAND()'))
                        ->limit(rand(10, 100))
                        ->get();

                    $account->channels()->sync($channels);
                }
            });
    }
}
