<?php

namespace Modulos\Seguranca\Providers\ActionButton\Facades;

use Illuminate\Support\Facades\Facade;

class ActionButton extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ActionButton';
    }
}
