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
    'Modulos\Seguranca\Events\ReloadCacheMenuEvent' => [
        'Modulos\Seguranca\Listeners\ReloadCacheMenuListener',
    ],
];
