<?php

namespace Modulos;

use Illuminate\Support\ServiceProvider;
use Route;

class ModulosServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $modulos = config('modulos.modulos');

        foreach ($modulos as $modulo) {
            // Load the routes for each of the modules
            if (file_exists(__DIR__ . '/' . $modulo . '/routes.php')) {
                Route::group([
                    'middleware' => 'web',
                    'namespace' => $modulo,
                ], function ($router) use ($modulo) {
                    require __DIR__ . '/' . $modulo . '/routes.php';
                });
            }

            // Load the views
            if (is_dir(__DIR__ . '/' . $modulo . '/Views')) {
                $this->loadViewsFrom(__DIR__ . '/' . $modulo . '/Views', $modulo);
            }

            if (is_dir(__DIR__ . '/' . $modulo . '/Database/Migrations')) {
                $this->loadMigrationsFrom(__DIR__ . '/' . $modulo . '/Database/Migrations');
            }
        }
    }

    public function register()
    {
    }
}
