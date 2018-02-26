<?php

namespace Harpia\Configuracao;

use Modulos\Geral\Models\Configuracao;
use Illuminate\Support\ServiceProvider;
use Harpia\Configuracao\Configuracao as Config;
use Modulos\Geral\Repositories\ConfiguracaoRepository;

/**
 * Class ConfiguracaoServiceProvider
 * @package Harpia\Configuracao
 * @codeCoverageIgnore
 */
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
