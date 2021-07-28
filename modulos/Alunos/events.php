<?php

/**
 *  Configuracoes de eventos do modulo Monitoramento
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
    'Modulos\Monitoramento\Events\HelloMonitoramento' => [
        'Modulos\Monitoramento\Listeners\MonitoramentoListener',
    ],
];
