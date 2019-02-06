<?php

namespace Modulos;

use Illuminate\Support\Facades\Event;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class ModulosEventServiceProvider extends ServiceProvider
{
    protected $listen = [];

    protected $subscribe = [];

    private $path;

    /**
     * ModulosEventServiceProvider constructor.
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    public function __construct(Application $app)
    {
        $this->path = base_path() . DIRECTORY_SEPARATOR . 'modulos';
        parent::__construct($app);
    }

    /**
     * Registra os events e listeners
     * configurados em cada modulo para a aplicacao
     */
    public function boot()
    {
        if (!chdir($this->path)) {
            return;
        }

        $directories = array_filter(glob('*'), 'is_dir');

        foreach ($directories as $directory) {
            if ($this->hasListenFile($directory)) {
                $this->registerListeners($directory);
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

    /**
     * Registra os listeners por diretorio
     * @param $directory
     */
    public function registerListeners($directory)
    {
        foreach ($this->listeners($directory) as $event => $listeners) {
            foreach ($listeners as $listener => $priority) {
                if (is_int($listener)) {
                    $listener = $priority;
                    $priority = 0;
                }

                Event::listen($event, $listener, $priority);
            }
        }
    }
}
