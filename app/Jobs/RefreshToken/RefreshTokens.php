<?php

namespace Korko\kTube\Jobs\RefreshToken;

use Carbon\Carbon;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Korko\kTube\Account;
use Korko\kTube\Jobs\Job;
use Log;
use Socialite;

class RefreshTokens extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string
     */
    public $queue = 'tokens';

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $accounts = Account::whereRaw('TIMESTAMPDIFF(MINUTE, NOW(), expires_at) <= 5')->canRefreshTokens()->with('site')->get();

        foreach ($accounts as $account) {
            try {
                $this->refreshToken($account);
            } catch (Exception $e) {
                Log::warning('Cannot refresh token for account '.$account->id.' (provider '.$account->site->provider.')');
            }
        }
    }

    protected function refreshToken(Account $account)
    {
        $token = Socialite::with($account->site->provider)->refreshToken($account->refresh_token);

        $account->update([
            'access_token' => $token->accessToken,
            'expires_at'   => Carbon::now()->addSeconds($token->expiresIn),
        ]);
    }
}
