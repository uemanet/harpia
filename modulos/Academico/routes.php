<?php

Route::group(['prefix' => 'academico', 'middleware' => ['auth']], function () {
    Route::controllers([
        'index' => '\Modulos\Academico\Http\Controllers\indexController',
        'polos' => '\Modulos\Academico\Http\Controllers\PolosController',
        'departamentos' => '\Modulos\Academico\Http\Controllers\DepartamentosController',
        'periodosletivos' => '\Modulos\Academico\Http\Controllers\PeriodosLetivosController',
        'cursos' => '\Modulos\Academico\Http\Controllers\CursosController',
        'centros' => '\Modulos\Academico\Http\Controllers\CentrosController',
        'ofertascursos' => '\Modulos\Academico\Http\Controllers\OfertasCursosController',
        'turmas' => '\Modulos\Academico\Http\Controllers\TurmasController',

    ]);
});

Route::group(['prefix' => 'academico/async', 'middleware' => ['auth']], function () {
    Route::controllers([
        'matrizescurriculares' => '\Modulos\Academico\Http\Controllers\Async\MatrizesCurriculares',
        'ofertascursos' => '\Modulos\Academico\Http\Controllers\Async\OfertasCursos'
    ]);
});
