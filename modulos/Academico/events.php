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
    'Modulos\Academico\Events\AcademicoEvent' => [
        'Modulos\Academico\Listeners\AcademicoListener',
    ],

    'Modulos\Academico\Events\NovoGrupoEvent' => [
        'Modulos\Integracao\Listeners\NovaSyncListener' => 10,
        'Modulos\Academico\Listeners\MigrarGrupoListener',
    ],

    'Modulos\Academico\Events\TutorVinculadoEvent' => [
        'Modulos\Integracao\Listeners\NovaSyncListener' => 10,
        'Modulos\Academico\Listeners\MigrarTutorVinculadoListener',
    ],
];
