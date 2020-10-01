<?php

/**
 *  Configuracoes de eventos do modulo RH
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
    'Modulos\RH\Events\HelloRH' => [
        'Modulos\RH\Listeners\RHListener',
    ],
];
