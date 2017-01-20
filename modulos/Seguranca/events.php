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
    'Modulos\Seguranca\Events\HelloSeguranca' => [
        'Modulos\Seguranca\Listeners\SegurancaListener',
    ],
];
