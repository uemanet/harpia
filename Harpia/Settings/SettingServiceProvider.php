<?php

namespace Harpia\Settings;

use Illuminate\Support\ServiceProvider;
use Modulos\Geral\Models\Configuracao;
use Modulos\Geral\Repositories\ConfiguracaoRepository;
use Modulos\Seguranca\Models\Modulo;
use Modulos\Seguranca\Repositories\ModuloRepository;

class SettingServiceProvider extends ServiceProvider
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
        $this->app['Setting'] = $this->app->share(function ($app) {
            $config = new ConfiguracaoRepository(new Configuracao());

            return new Setting($config);
        });
    }
}
