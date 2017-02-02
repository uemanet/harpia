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

    /*
     * Evento de updates em sincronizacao
     * */

    'Modulos\Integracao\Events\AtualizarSyncEvent' => [
        'Modulos\Integracao\Listeners\AtualizarSyncListener'
    ],

    'Modulos\Integracao\Events\TurmaMapeadaEvent' => [
        'Modulos\Integracao\Listeners\NovaSyncListener' => 10,
        'Modulos\Integracao\Listeners\MigrarTurmaListener'
    ],
];
