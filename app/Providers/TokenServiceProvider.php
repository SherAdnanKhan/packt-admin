<?php

namespace App\Providers;

use App\Services\Token\Lcobucci;
use Lcobucci\JWT\Builder as JWTBuilder;
use Lcobucci\JWT\Parser as JWTParser;
use Tymon\JWTAuth\Claims\Factory as ClaimFactory;
use Tymon\JWTAuth\Http\Parser\AuthHeaders;
use Tymon\JWTAuth\Http\Parser\Cookies;
use Tymon\JWTAuth\Http\Parser\InputSource;
use Tymon\JWTAuth\Http\Parser\Parser;
use Tymon\JWTAuth\Http\Parser\QueryString;
use Tymon\JWTAuth\Http\Parser\RouteParams;

class TokenServiceProvider extends \Tymon\JWTAuth\Providers\LaravelServiceProvider
{
    /**
     * @inheritDoc
     */
    protected function registerTokenParser()
    {
        $this->app->singleton('tymon.jwt.parser', function ($app) {
            $tokenName = config('app.token_name');
            $parser = new Parser($app['request'], [
                new AuthHeaders(),
                (new QueryString())->setKey($tokenName),
                (new InputSource())->setKey($tokenName),
                (new RouteParams())->setKey($tokenName),
                (new Cookies($this->config('decrypt_cookies')))->setKey($tokenName),
            ]);

            $app->refresh('request', $parser, 'setRequest');

            return $parser;
        });
    }

    /**
     * @inheritDoc
     */
    protected function registerClaimFactory()
    {
        $this->app->singleton('tymon.jwt.claim.factory', function ($app) {
            $factory = new ClaimFactory($app['request']);
            $app->refresh('request', $factory, 'setRequest');

            return $factory
                ->setTTL($this->config('ttl'))
                ->setLeeway($this->config('leeway'))
                ->extend('exp', \App\Services\Token\ExpirationClaim::class);
        });
    }

    /**
     * Register the bindings for the Lcobucci JWT provider.
     *
     * @return void
     */
    protected function registerLcobucciProvider()
    {
        $this->app->singleton('tymon.jwt.provider.jwt.lcobucci', function ($app) {
            return new Lcobucci(
                new JWTBuilder(),
                new JWTParser(),
                $this->config('secret'),
                $this->config('algo'),
                $this->config('keys')
            );
        });
    }
}
