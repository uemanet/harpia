<?php

namespace Harpia\Format;

use Illuminate\Support\ServiceProvider;

class FormatServiceProvider extends ServiceProvider
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
        $this->app->singleton('Format', function ($app) {
            return new Format();
        });
    }
}
