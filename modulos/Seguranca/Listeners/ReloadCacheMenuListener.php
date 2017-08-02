<?php

namespace Modulos\Seguranca\Listeners;
use Illuminate\Foundation\Application;
use Modulos\Seguranca\Events\ReloadCacheMenuEvent;
use Modulos\Seguranca\Providers\Seguranca\Seguranca;
use Cache;

class ReloadCacheMenuListener
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function handle(ReloadCacheMenuEvent $event)
    {
        $seguranca = $this->app[Seguranca::class];

        Cache::forget('MENU_'.$this->app['auth']->user()->usr_id);

        $seguranca->makeCacheMenu();
    }
}