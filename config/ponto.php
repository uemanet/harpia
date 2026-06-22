<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuracoes do Modulo de Ponto
    |--------------------------------------------------------------------------
    */
    'polling' => [
        'enabled' => env('PONTO_POLLING_ENABLED', true),
        'cron' => env('PONTO_POLLING_CRON', '*/5 * * * *'),
        'limit' => env('PONTO_POLLING_LIMIT', 500),
    ],
];
