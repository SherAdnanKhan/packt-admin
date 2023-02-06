<?php

namespace App\Services\Token;

use Illuminate\Support\Collection;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Lcobucci extends \Tymon\JWTAuth\Providers\JWT\Lcobucci
{
    public function decode($token)
    {
        try {
            $jwt = $this->parser->parse($token);
        } catch (\Exception $e) {
            throw new TokenInvalidException('Could not decode token: ' . $e->getMessage(), $e->getCode(), $e);
        }

        //        if (! $jwt->verify($this->signer, $this->getVerificationKey())) {
        //            throw new TokenInvalidException('Token Signature could not be verified.');
        //        }

        return (new Collection($jwt->getClaims()))
            ->map(function ($claim) {
                return is_object($claim) ? $claim->getValue() : $claim;
            })
            ->toArray();
    }
}
