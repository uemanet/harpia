<?php

namespace Modulos\Seguranca\Providers\MasterMenu;

use Illuminate\Support\ServiceProvider;

class MasterMenuProvider extends ServiceProvider
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
        $this->app['MasterMenu'] = $this->app->share(function ($app) {
            return new MasterMenu($app);
        });
    }
}
