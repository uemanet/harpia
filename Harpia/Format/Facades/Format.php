<?php

namespace Harpia\Format\Facades;

use Illuminate\Support\Facades\Facade;

class Format extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Format';
    }
}
