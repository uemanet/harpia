<?php

namespace Modulos\Seguranca\Providers\MasterMenu\Facades;

use Illuminate\Support\Facades\Facade;

class MasterMenu extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'MasterMenu';
    }
}
