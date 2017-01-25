<?php

/**
 *  Configuracoes de eventos do modulo Integracao
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
    'Modulos\Integracao\Events\HelloIntegracao' => [
        'Modulos\Integracao\Listeners\IntegracaoListener' => 10,
    ],
];
