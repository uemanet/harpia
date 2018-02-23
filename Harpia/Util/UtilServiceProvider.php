<?php

namespace Harpia\Util;

use Illuminate\Support\ServiceProvider;

/**
 * Class UtilServiceProvider
 * @package Harpia\Util
 * @codeCoverageIgnore
 */
class UtilServiceProvider extends ServiceProvider
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
        $this->app->singleton('Util', function ($app) {
            return new Util();
        });
    }
}
