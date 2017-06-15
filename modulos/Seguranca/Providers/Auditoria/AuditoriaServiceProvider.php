<?php

namespace Modulos\Seguranca\Providers\Auditoria;

use Illuminate\Support\ServiceProvider;
use Modulos\Academico\Models\Polo;

class AuditoriaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Models do Módulo Acadêmico

        Polo::observe(AuditoriaObserver::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
