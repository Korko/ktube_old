<?php

namespace Korko\kTube\Jobs;

use Carbon\Carbon;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Korko\kTube\Account;
use Socialite;

class RefreshTokens extends Job implements SelfHandling, ShouldQueue {

    use InteractsWithQueue, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $accounts = Account::whereRaw('TIMESTAMPDIFF(MINUTE, NOW(), expires_at) <= 5')->with('site')->get();
        foreach ($accounts as $account) {
            $this->refreshToken($account);
        }
    }

    protected function refreshToken(Account $account)
    {
        $token = Socialite::with($account->site->name)->refreshToken($account->refresh_token);

        Account::with('provider', $account->site->name)
            ->with('access_token', $account->refresh_token)
            ->update([
                'access_token' => $token->accessToken,
                'expires_at'   => Carbon::now()->addSeconds($token->expiresIn)
            ]);
    }
}