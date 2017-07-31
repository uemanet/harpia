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

    'Modulos\Integracao\Events\AtualizarSyncDeleteEvent' => [
        'Modulos\Integracao\Listeners\AtualizarSyncDeleteListener'
    ],

    'Modulos\Integracao\Events\TurmaMapeadaEvent' => [
        'Modulos\Integracao\Listeners\NovaSyncListener' => 10,
        'Modulos\Integracao\Listeners\TurmaMapeadaListener'
    ],

    'Modulos\Integracao\Events\TurmaRemovidaEvent' => [
        'Modulos\Integracao\Listeners\NovaSyncListener' => 10,
        'Modulos\Integracao\Listeners\TurmaRemovidaListener'
    ],

    'Modulos\Integracao\Events\MapearNotasEvent' => [
        'Modulos\Integracao\Listeners\NovaSyncListener' => 10
    ],

    'Modulos\Integracao\Events\SincronizacaoEvent' => [
        'Modulos\Integracao\Listeners\Sincronizacao\TurmaListener',
        'Modulos\Integracao\Listeners\Sincronizacao\GrupoListener',
        'Modulos\Integracao\Listeners\Sincronizacao\TutorListener',
        'Modulos\Integracao\Listeners\Sincronizacao\UsuarioListener',
        'Modulos\Integracao\Listeners\Sincronizacao\DisciplinaListener',
        'Modulos\Integracao\Listeners\Sincronizacao\AlunoGrupoListener',
        'Modulos\Integracao\Listeners\Sincronizacao\MatriculaCursoListener',
        'Modulos\Integracao\Listeners\Sincronizacao\MatriculaDisciplinaListener'
    ]
];
