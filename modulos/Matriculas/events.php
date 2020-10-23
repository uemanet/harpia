<?php

/**
 *  Configuracoes de eventos do modulo Matriculas
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
    'Modulos\Matriculas\Events\HelloMatriculas' => [
        'Modulos\Matriculas\Listeners\MatriculasListener',
    ],
];
