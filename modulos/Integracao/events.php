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

    'Modulos\Integracao\Events\UpdateSincronizacaoEvent' => [
        'Modulos\Integracao\Listeners\UpdateSincronizacaoListener'
    ],

    'Modulos\Integracao\Events\DeleteSincronizacaoEvent' => [
        'Modulos\Integracao\Listeners\DeleteSincronizacaoListener'
    ],

    'Modulos\Integracao\Events\TurmaMapeadaEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Integracao\Listeners\TurmaMapeadaListener',
        'Modulos\Integracao\Listeners\TurmaMapeadaV2Listener'
    ],

    'Modulos\Integracao\Events\TurmaRemovidaEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Integracao\Listeners\TurmaRemovidaListener',
        'Modulos\Integracao\Listeners\TurmaRemovidaV2Listener'
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
