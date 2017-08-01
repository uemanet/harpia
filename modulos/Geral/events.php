<?php

/**
 *  Configuracoes de eventos do modulo Geral
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
    'Modulos\Geral\Events\HelloGeral' => [
        'Modulos\Geral\Listeners\GeralListener',
    ],

    'Modulos\Geral\Events\AtualizarPessoaEvent' => [
        'Modulos\Integracao\Listeners\SincronizacaoListener' => 10,
        'Modulos\Geral\Listeners\MigrarAtualizarPessoaListener',
    ]
];
