<?php

namespace Uemanet\EloquentTable\Provider;

use Illuminate\Support\ServiceProvider;

class EloquentTableServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    public function boot() {
        $this->publishes([
            __DIR__ . '/../../config/eloquent-table.php' => config_path('eloquenttable.php'),
        ], 'config');

        $this->loadViewsFrom(__DIR__ . '/../../views', 'eloquenttable');
        $this->publishes([
            __DIR__ . '/../../views' => resource_path('views/uemanet/eloquenttable')
        ], 'views');

        include __DIR__ . '/../helpers.php';
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('eloquenttable');
    }
}