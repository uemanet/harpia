<?php

namespace Modulos\Seguranca\Providers\Menu;

use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
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
        $this->app['Menu'] = $this->app->share(function($app){
            return new Menu($app['request'], $app['auth']);
        });
    }
}