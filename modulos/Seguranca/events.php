<?php

/**
 *  Configuracoes de eventos do modulo Seguranca
 *
 * 'EventClass' => [
 *      'FirstListenerClass',
 *      'SecondListenerClass' => $priorityValue
 * ]
 *
 * @see Illuminate\Contracts\Events\Dispatcher
 * @see Illuminate\Events\Dispatcher
 */

return [
    'Modulos\Seguranca\Events\ReloadCacheEvent' => [
        'Modulos\Seguranca\Listeners\ReloadCacheListener',
    ],

    'Modulos\Seguranca\Events\LogoutOtherDevicesEvent' => [
        'Modulos\Seguranca\Listeners\LogoutOtherDevicesListener'
    ],
];
