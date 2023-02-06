<?php

namespace App\Providers;

use App\Services\Api\SubscriptionHttpService;
use App\Services\Api\AccountHttpService;
use App\Services\Api\UsersHttpService;
use App\Services\Api\ProductHttpService;
use App\Services\Api\OrdersHttpService;
use App\Services\Api\PriceHttpService;
use App\Services\Api\ProductAvailableHttpService;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $baseUrl = config('services.auth.uri');
        $this->app->singleton(AccountHttpService::class, function () use ($baseUrl) {
            return new AccountHttpService($baseUrl);
        });

        $baseUrl = config('services.ms.subscription');
        $this->app->singleton(SubscriptionHttpService::class, function () use ($baseUrl) {
            return new SubscriptionHttpService($baseUrl);
        });

        $baseUrl = config('services.users.uri');
        $this->app->singleton(UsersHttpService::class, function () use ($baseUrl) {
            return new UsersHttpService($baseUrl);
        });

        $baseUrl = config('services.product.uri');
        $this->app->singleton(ProductHttpService::class, function () use ($baseUrl) {
            return new ProductHttpService($baseUrl);
        });

        $baseUrl = config('services.orders.uri');
        $this->app->singleton(OrdersHttpService::class, function () use ($baseUrl) {
            return new OrdersHttpService($baseUrl);
        });

        $baseUrl = config('services.product.price.uri');
        $this->app->singleton(PriceHttpService::class, function () use ($baseUrl) {
            return new PriceHttpService($baseUrl);
        });

        $baseUrl = config('services.product.availability');
        $this->app->singleton(ProductAvailableHttpService::class, function () use ($baseUrl) {
            return new ProductAvailableHttpService($baseUrl);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
