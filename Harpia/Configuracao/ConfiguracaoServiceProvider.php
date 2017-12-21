<?php

namespace Harpia\Configuracao;

use Illuminate\Support\ServiceProvider;
use Modulos\Geral\Models\Configuracao;
use Modulos\Geral\Repositories\ConfiguracaoRepository;
use Harpia\Configuracao\Configuracao as Config;

class ConfiguracaoServiceProvider extends ServiceProvider
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
        $this->app->singleton('Configuracao', function ($app) {
            $config = new ConfiguracaoRepository(new Configuracao());

            return new Config($config);
        });
    }
}
