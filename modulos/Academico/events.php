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

    'Modulos\Academico\Events\CreateVinculoTutorEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\CreateVinculoTutorListener',
    ],

    'Modulos\Academico\Events\CreateOfertaDisciplinaEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\CreateOfertaDisciplinaListener',
    ],

    'Modulos\Academico\Events\AtualizarGrupoEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\MigrarAtualizaGrupoListener'
    ],

    'Modulos\Academico\Events\DeleteGrupoEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\MigrarExclusaoGrupoListener',
    ],

    'Modulos\Academico\Events\DeleteOfertaDisciplinaEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\MigrarExclusaoOfertaDisciplinaListener',
    ],

    'Modulos\Academico\Events\DeleteTutorVinculadoEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\MigrarExclusaoTutorVinculadoListener',
    ],

    'Modulos\Academico\Events\AlterarGrupoAlunoEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\MigrarAlterarGrupoAlunoListener',
    ],

    'Modulos\Academico\Events\DeletarGrupoAlunoEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\MigrarDeletarGrupoAlunoListener',
    ],

    'Modulos\Academico\Events\AtualizarSituacaoMatriculaEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\MigrarAtualizarSituacaoMatriculaListener',
    ],

    'Modulos\Academico\Events\AtualizarMatriculaCursoEvent' => [
        'Modulos\Academico\Listeners\AtualizarMatriculaCursoListener'
    ],

    'Modulos\Academico\Events\AlterarProfessorOfertaDisciplinaEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Academico\Listeners\MigrarAlteracaoProfessorOfertaDisciplinaListener'
    ],
];
