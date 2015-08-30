<?php

namespace Korko\kTube\Jobs\RefreshToken;

use Carbon\Carbon;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Korko\kTube\Account;
use Korko\kTube\Jobs\Job;
use Socialite;

class RefreshToken extends Job implements SelfHandling, ShouldQueue {

    use InteractsWithQueue, SerializesModels;

    protected $provider;
    protected $token;

    /**
     * Create a new job instance.
     *
     * @param  string  $provider
     * @param  string  $token
     * @return void
     */
    public function __construct($provider, $token)
    {
        $this->provider = $provider;
        $this->token = $token;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $token = Socialite::with($this->provider)->refreshToken($this->token);

        Account::with('provider', $this->provider)
            ->with('access_token', $this->token)
            ->update([
                'access_token' => $token->accessToken,
                'expires_at' => Carbon::now()->addSeconds($token->expiresIn)
            ]);
    }
}