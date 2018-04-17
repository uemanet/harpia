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
    'Modulos\Academico\Events\CreateMatriculaTurmaEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\CreateMatriculaTurmaListener'
    ],

    'Modulos\Academico\Events\UpdateTurmaEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\UpdateTurmaListener'
    ],

    'Modulos\Academico\Events\CreateGrupoEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\CreateGrupoListener',
    ],

    'Modulos\Academico\Events\CreateMatriculaDisciplinaEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\CreateMatriculaDisciplinaListener',
    ],

    'Modulos\Academico\Events\CreateMatriculaDisciplinaLoteEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\CreateMatriculaDisciplinaLoteListener',
    ],

    'Modulos\Academico\Events\CreateVinculoTutorEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\CreateVinculoTutorListener',
    ],

    'Modulos\Academico\Events\CreateOfertaDisciplinaEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\CreateOfertaDisciplinaListener',
    ],

    'Modulos\Academico\Events\UpdateGrupoEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\UpdateGrupoListener'
    ],

    'Modulos\Academico\Events\DeleteGrupoEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\DeleteGrupoListener',
    ],

    'Modulos\Academico\Events\DeleteOfertaDisciplinaEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\DeleteOfertaDisciplinaListener',
    ],

    'Modulos\Academico\Events\DeleteVinculoTutorEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\DeleteVinculoTutorListener',
    ],

    'Modulos\Academico\Events\UpdateGrupoAlunoEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\UpdateGrupoAlunoListener',
    ],

    'Modulos\Academico\Events\DeleteGrupoAlunoEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\DeleteGrupoAlunoListener',
    ],

    'Modulos\Academico\Events\UpdateSituacaoMatriculaEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\UpdateSituacaoMatriculaListener',
    ],

    'Modulos\Academico\Events\UpdateMatriculaCursoEvent' => [
        'Modulos\Academico\Listeners\UpdateMatriculaCursoListener'
    ],

    'Modulos\Academico\Events\UpdateProfessorDisciplinaEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\UpdateProfessorDisciplinaListener'
    ],
];
