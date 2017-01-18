<?php

namespace Modulos\Academico\Listeners;

use Modulos\Academico\Events\HelloAcademico;
use Config;

class AcademicoListener
{
    public function __construct()
    {
    }

    public function handle(HelloAcademico $event)
    {
        Config::set('event_test', $event->getData(), 'geral');
        return true;
    }
}
