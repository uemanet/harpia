<?php

namespace Harpia\Moodle;

use Illuminate\Support\ServiceProvider;
use Modulos\Integracao\Models\AmbienteVirtual;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;

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
        $this->app['Moodle'] = $this->app->share(function ($app) {
            return new Moodle();
        });
    }
}
