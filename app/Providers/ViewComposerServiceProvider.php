<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('layout', 'App\Http\Composers\SideNavComposer');
        view()->composer('pages.manual_order', 'App\Http\Composers\ManualOrderComposer');
        view()->composer('pages.new_account', 'App\Http\Composers\NewAccountComposer');
        view()->composer('partials.*', 'App\Http\Composers\PartialsComposer');
    }
}
