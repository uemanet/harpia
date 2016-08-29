<?php

Route::group(['prefix' => 'academico', 'middleware' => ['auth']], function () {
    Route::controllers([
        'index' => '\Modulos\Academico\Http\Controllers\indexController',
        'polos' => '\Modulos\Academico\Http\Controllers\PolosController',
        'departamentos' => '\Modulos\Academico\Http\Controllers\DepartamentosController',
        'periodosletivos' => '\Modulos\Academico\Http\Controllers\PeriodosLetivosController',
        'cursos' => '\Modulos\Academico\Http\Controllers\CursosController',
        'grupos' => '\Modulos\Academico\Http\Controllers\GruposController',
    ]);

    Route::group(['prefix' => 'async', 'middleware' => ['auth']], function () {
        Route::controllers([
            'turmas' => '\Modulos\Academico\Http\Controllers\Async\Turmas',
            'polos' => '\Modulos\Academico\Http\Controllers\Async\Polos'
        ]);
    });
});
