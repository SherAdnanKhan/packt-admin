<?php

use Illuminate\Support\Facades\Cookie;
use Tymon\JWTAuth\Facades\JWTAuth;

if (!function_exists('token')) {
    function token()
    {
        $token = JWTAuth::getToken();
        if ($token) {
            $token = $token->get();
        }
        return $token;
    }
}

if (!function_exists('set_tokens')) {
    /**
     * @param array $tokens ['refresh'=> ..., 'access'=> ...]
     */
    function set_tokens(array $tokens)
    {
        Cookie::queue(
            \cookie(
                config('app.refresh_token_name'),
                $tokens['refresh'],
                null,
                null,
                config('app.session_domain '),
                false,
                false
            )
        );
        Cookie::queue(
            \cookie(config('app.token_name'), $tokens['access'], null, null, config('app.session_domain'), false, false)
        );
        JWTAuth::setToken($tokens['access']);
    }
}
