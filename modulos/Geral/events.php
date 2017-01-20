<?php

/**
 *  Configuracoes de eventos do modulo Geral
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
    'Modulos\Geral\Events\HelloGeral' => [
        'Modulos\Geral\Listeners\GeralListener',
    ],
];
