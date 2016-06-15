<?php

namespace Modulos;

use Illuminate\Support\ServiceProvider;

class ModulosServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $modulos = config('modulos.modulos');

        while (list(, $modulo) = each($modulos)) {

            // Load the routes for each of the modules
            if (file_exists(__DIR__.'/'.$modulo.'/routes.php')) {
                include __DIR__.'/'.$modulo.'/routes.php';
            }

            // Load the views
            if (is_dir(__DIR__.'/'.$modulo.'/Views')) {
                $this->loadViewsFrom(__DIR__.'/'.$modulo.'/Views', $modulo);
            }
        }
    }

    public function register()
    {
    }
}