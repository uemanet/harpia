<?php

namespace Harpia\Moodle;

use Illuminate\Support\ServiceProvider;

/**
 * Class MoodleServiceProvider
 * @package Harpia\Moodle
 * @codeCoverageIgnore
 */
class MoodleServiceProvider extends ServiceProvider
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
        $this->app->singleton('Moodle', function ($app) {
            return new Moodle();
        });
    }
}
