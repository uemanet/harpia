<?php

namespace Harpia\Moodle\Facades;

use Illuminate\Support\Facades\Facade;

class Moodle extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Moodle';
    }
}
