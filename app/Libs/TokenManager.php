<?php

namespace Korko\kTube\Libs;

use Korko\kTube\Libs\Youtube;
use Korko\kTube\Token;
use Carbon\Carbon;

class TokenManager
{
    public static function refreshAll()
    {
        $tokens = Token::all();
        foreach ($tokens as $token) {
            switch ($token->type) {
                case 'youtube':
                    $data = Youtube::refreshToken($token);

                    $token->access_token = $data->access_token;
                    $token->expires_at = Carbon::now()->addSeconds($data->expires_in);
                    break;
            }
            $token->save();
        }
    }
}
