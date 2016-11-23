<?php

namespace Harpia\Configuracao\Facades;

use Illuminate\Support\Facades\Facade;

class Configuracao extends Facade
{
    /**
     * Get the registered name of the component.
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Configuracao';
    }
}
