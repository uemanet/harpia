<?php

Route::group(['prefix' => 'academico', 'middleware' => ['auth']], function () {
    Route::controllers([
        'index' => '\Modulos\Academico\Http\Controllers\indexController',
        'departamentos' => '\Modulos\Academico\Http\Controllers\DepartamentosController'
    ]);
});
