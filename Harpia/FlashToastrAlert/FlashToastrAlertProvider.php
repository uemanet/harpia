<?php

namespace Harpia\FlashToastrAlert;

use Illuminate\Support\ServiceProvider;

class FlashToastrAlertProvider extends ServiceProvider
{
    protected $defer = false;

    public function boot()
    {
        //
    }

    public function register()
    {
        $this->app->singleton('flash', function ($app) {
            return new FlashToastrAlert($app['session'], $app['config']);
        });
    }

    public function provides()
    {
        return ['flash'];
    }
}
