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
        'Modulos\Academico\Listeners\CreateMatriculaTurmaV2Listener',
        'Modulos\Academico\Listeners\CreateMatriculaTurmaListener'
    ],

    'Modulos\Academico\Events\DeleteMatriculaTurmaEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\DeleteMatriculaTurmaListener',
        'Modulos\Academico\Listeners\DeleteMatriculaTurmaV2Listener'
    ],

    'Modulos\Academico\Events\UpdateTurmaEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\UpdateTurmaListener'
    ],

    'Modulos\Academico\Events\CreateGrupoEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\CreateGrupoListener',
        'Modulos\Academico\Listeners\CreateGrupoV2Listener'
    ],

    'Modulos\Academico\Events\DeleteMatriculaDisciplinaEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\DeleteMatriculaDisciplinaListener',
        'Modulos\Academico\Listeners\DeleteMatriculaDisciplinaV2Listener',
    ],

    'Modulos\Academico\Events\CreateMatriculaDisciplinaEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\CreateMatriculaDisciplinaListener',
        'Modulos\Academico\Listeners\CreateMatriculaDisciplinaV2Listener'
    ],

    'Modulos\Academico\Events\CreateMatriculaDisciplinaLoteEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\CreateMatriculaDisciplinaLoteListener',
        'Modulos\Academico\Listeners\CreateMatriculaDisciplinaLoteV2Listener',
    ],

    'Modulos\Academico\Events\DeleteMatriculaDisciplinaLoteEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\DeleteMatriculaDisciplinaLoteListener',
    ],

    'Modulos\Academico\Events\CreateVinculoTutorEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\CreateVinculoTutorListener',
        'Modulos\Academico\Listeners\CreateVinculoTutorV2Listener',
    ],

    'Modulos\Academico\Events\CreateOfertaDisciplinaEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\CreateOfertaDisciplinaV2Listener',
        'Modulos\Academico\Listeners\CreateOfertaDisciplinaListener',
    ],

    'Modulos\Academico\Events\UpdateGrupoEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\UpdateGrupoListener',
        'Modulos\Academico\Listeners\UpdateGrupoV2Listener'
    ],

    'Modulos\Academico\Events\DeleteGrupoEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\DeleteGrupoListener',
        'Modulos\Academico\Listeners\DeleteGrupoV2Listener',

    ],

    'Modulos\Academico\Events\DeleteOfertaDisciplinaEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\DeleteOfertaDisciplinaListener',
        'Modulos\Academico\Listeners\DeleteOfertaDisciplinaV2Listener',
    ],

    'Modulos\Academico\Events\DeleteVinculoTutorEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\DeleteVinculoTutorListener',
        'Modulos\Academico\Listeners\DeleteVinculoTutorV2Listener'
    ],

    'Modulos\Academico\Events\UpdateGrupoAlunoEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\UpdateGrupoAlunoListener',
        'Modulos\Academico\Listeners\UpdateGrupoAlunoV2Listener',
    ],

    'Modulos\Academico\Events\DeleteGrupoAlunoEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\DeleteGrupoAlunoListener',
        'Modulos\Academico\Listeners\DeleteGrupoAlunoV2Listener',
    ],

    'Modulos\Academico\Events\UpdateSituacaoMatriculaEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\UpdateSituacaoMatriculaListener',
        'Modulos\Academico\Listeners\UpdateSituacaoMatriculaV2Listener',
    ],

    'Modulos\Academico\Events\UpdateMatriculaCursoEvent' => [
        'Modulos\Academico\Listeners\UpdateMatriculaCursoListener'
    ],

    'Modulos\Academico\Events\UpdateProfessorDisciplinaEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\UpdateProfessorDisciplinaListener',
        'Modulos\Academico\Listeners\UpdateProfessorDisciplinaV2Listener'
    ],
];
