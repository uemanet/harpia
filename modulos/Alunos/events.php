<?php

/**
 *  Configuracoes de eventos do modulo Alunos
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
    'Modulos\Alunos\Events\HelloAlunos' => [
        'Modulos\Alunos\Listeners\AlunosListener',
    ],
];
