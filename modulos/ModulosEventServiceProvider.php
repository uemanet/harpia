<?php

namespace Modulos;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class ModulosEventServiceProvider extends ServiceProvider
{
    protected $listen = [];

    protected $subscribe = [];

    /**
     * Registra os events e listeners
     * configurados em cada modulo para a aplicacao
     */
    public function boot()
    {
        $directories = array_filter(glob('*'), 'is_dir');

        foreach ($directories as $directory) {
            if ($this->hasListenFile($directory)) {
                foreach ($this->listeners($directory) as $event => $listeners) {
                    foreach ($listeners as $listener) {
                        Event::listen($event, $listener);
                    }
                }
            }
        }

        foreach ($this->subscribe as $subscriber) {
            Event::subscribe($subscriber);
        }
    }

    /**
     * Retorna o array de configuracao de listeners de cada diretorio
     * @param $directory
     * @return mixed
     */
    public function listeners($directory)
    {
        return include $directory . DIRECTORY_SEPARATOR . 'events.php';
    }

    /**
     * Verifica se um diretorio tem o arquivo de configuracao de listeners
     * @param $directory
     * @return bool
     */
    public function hasListenFile($directory)
    {
        return file_exists($directory . DIRECTORY_SEPARATOR . 'events.php');
    }
}
