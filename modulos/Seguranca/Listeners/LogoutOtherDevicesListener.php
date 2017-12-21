<?php

namespace Modulos\Seguranca\Listeners;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modulos\Seguranca\Events\LogoutOtherDevicesEvent;

class LogoutOtherDevicesListener
{
    public function handle(LogoutOtherDevicesEvent $event)
    {
        $sessionId = $event->getRequest()->session()->getId();

        DB::table('sessions')->where('user_id', Auth::user()->usr_id)
                            ->where('id', '<>', $sessionId)
                            ->delete();
    }
}