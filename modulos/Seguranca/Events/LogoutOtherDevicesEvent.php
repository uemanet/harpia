<?php

namespace Modulos\Seguranca\Events;

use App\Events\Event;
use Illuminate\Http\Request;

class LogoutOtherDevicesEvent extends Event
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getRequest()
    {
        return $this->request;
    }
}