<?php

/**
 *  Configuracoes de eventos do modulo academico
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
    'Modulos\Academico\Events\MatriculaAlunoTurmaEvent' => [
        'Modulos\Integracao\Listeners\NovaSyncListener' => 10,
        'Modulos\Academico\Listeners\MigrarMatriculaAlunoTurmaListener'
    ],

    'Modulos\Academico\Events\NovoGrupoEvent' => [
        'Modulos\Integracao\Listeners\NovaSyncListener' => 10,
        'Modulos\Academico\Listeners\MigrarGrupoListener',
    ],
    'Modulos\Academico\Events\NovaMatriculaDisciplinaEvent' => [
        'Modulos\Integracao\Listeners\NovaSyncListener' => 10,
        'Modulos\Academico\Listeners\MigrarMatriculaDisciplinaListener',
    ],

    'Modulos\Academico\Events\TutorVinculadoEvent' => [
        'Modulos\Integracao\Listeners\NovaSyncListener' => 10,
        'Modulos\Academico\Listeners\MigrarTutorVinculadoListener',
    ],

    'Modulos\Academico\Events\OfertaDisciplinaEvent' => [
        'Modulos\Integracao\Listeners\NovaSyncListener' => 10,
        'Modulos\Academico\Listeners\MigrarOfertaDisciplinaListener',
    ]
];
