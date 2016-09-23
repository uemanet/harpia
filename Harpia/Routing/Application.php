<?php

namespace Harpia\Routing;

use Illuminate\Events\EventServiceProvider;
use Illuminate\Foundation\Application as BaseApplication;

class Application extends BaseApplication
{
    /**
     * Register all of the base service providers.
     *
     * @return void
     */
    protected function registerBaseServiceProviders()
    {
        $this->register(new EventServiceProvider($this));
        $this->register(new \Harpia\Routing\RoutingServiceProvider($this));
    }
}