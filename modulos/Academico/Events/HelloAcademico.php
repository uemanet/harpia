<?php

namespace Modulos\Academico\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Config;

class HelloAcademico extends Event
{
    use SerializesModels;

    private $message;

    public function __construct($message)
    {
        Config::set('event_test', $this->message, 'geral');
        $this->message = $message;
    }
}
