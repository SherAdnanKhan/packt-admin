<?php

namespace App\Services\Token;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class ExpirationClaim extends \Tymon\JWTAuth\Claims\Expiration
{
    public function validatePayload()
    {
        if ($this->isPast($this->getValue())) {
            $refreshToken = Cookie::get(config('app.refresh_token_name'));
            $token = Cookie::get(config('app.token_name'));

            if ($refreshToken) {
                $response = Http::withToken($token)
                    ->asJson()
                    ->send('POST', config('services.auth.uri') . '/users/me/tokens', [
                        'body' => json_encode(['refresh' => $refreshToken]),
                    ]);
                if ($response->successful()) {
                    $data = $response->json()['data'];
                    set_tokens($data);
                    return;
                }
            }
            throw new TokenExpiredException('Token has expired');
        }
    }
}
