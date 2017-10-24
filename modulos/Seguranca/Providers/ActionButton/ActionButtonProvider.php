<?php

namespace Modulos\Seguranca\Providers\ActionButton;

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
        $this->app->singleton('ActionButton', function ($app) {
            return new ActionButton($app);
        });
    }
}
