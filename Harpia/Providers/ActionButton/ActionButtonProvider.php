<?php

namespace Harpia\Providers\ActionButton;

use Illuminate\Support\ServiceProvider;

class ActionButtonProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['ActionButton'] = $this->app->share(function($app){
            return new ActionButton($app);
        });
    }
}