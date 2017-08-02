<?php

namespace Modulos\Seguranca\Listeners;
use Illuminate\Foundation\Application;
use Modulos\Seguranca\Events\ReloadCacheEvent;
use Modulos\Seguranca\Providers\Seguranca\Seguranca;
use Cache;

class ReloadCacheListener
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function handle(ReloadCacheEvent $event)
    {
        $seguranca = $this->app[Seguranca::class];

        Cache::forget('MENU_'.$this->app['auth']->user()->usr_id);
        Cache::forget('PERMISSOES_'.$this->app['auth']->user()->usr_id);

        $seguranca->makeCachePermissoes();
        $seguranca->makeCacheMenu();
    }
}