<?php

Route::group(['prefix' => 'academico', 'middleware' => ['auth']], function () {

    Route::group(['prefix' => 'index'], function () {
        Route::get('/', '\Modulos\Academico\Http\Controllers\indexController@getIndex');
        Route::get('/index', '\Modulos\Academico\Http\Controllers\indexController@getIndex');
    });

    Route::group(['prefix' => 'polos'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\PolosController@getIndex');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\PolosController@getCreate');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\PolosController@postCreate');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\PolosController@getEdit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\PolosController@putEdit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\PolosController@postDelete');
    });

    Route::group(['prefix' => 'departamentos'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\DepartamentosController@getIndex');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\DepartamentosController@getCreate');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\DepartamentosController@postCreate');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\DepartamentosController@getEdit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\DepartamentosController@putEdit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\DepartamentosController@postDelete');
    });

    Route::group(['prefix' => 'periodosletivos'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\PeriodosLetivosController@getIndex');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\PeriodosLetivosController@getCreate');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\PeriodosLetivosController@postCreate');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\PeriodosLetivosController@getEdit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\PeriodosLetivosController@putEdit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\PeriodosLetivosController@postDelete');
    });

    Route::group(['prefix' => 'cursos'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\CursosController@getIndex');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\CursosController@getCreate');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\CursosController@postCreate');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\CursosController@getEdit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\CursosController@putEdit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\CursosController@postDelete');
    });

    Route::group(['prefix' => 'matrizescurriculares'], function () {
        Route::get('/index/{id}', '\Modulos\Academico\Http\Controllers\MatrizesCurricularesController@getIndex');
        Route::get('/create/{id}', '\Modulos\Academico\Http\Controllers\MatrizesCurricularesController@getCreate');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\MatrizesCurricularesController@postCreate');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\MatrizesCurricularesController@getEdit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\MatrizesCurricularesController@putEdit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\MatrizesCurricularesController@postDelete');
    });

    Route::group(['prefix' => 'centros'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\CentrosController@getIndex');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\CentrosController@getCreate');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\CentrosController@postCreate');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\CentrosController@getEdit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\CentrosController@putEdit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\CentrosController@postDelete');
    });

    Route::group(['prefix' => 'ofertascursos'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\OfertasCursosController@getIndex');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\OfertasCursosController@getCreate');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\OfertasCursosController@postCreate');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\OfertasCursosController@postDelete');
    });

    Route::group(['prefix' => 'grupos'], function () {
        Route::get('/index/{id}', '\Modulos\Academico\Http\Controllers\GruposController@getIndex');
        Route::get('/create/{id}', '\Modulos\Academico\Http\Controllers\GruposController@getCreate');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\GruposController@postCreate');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\GruposController@getEdit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\GruposController@putEdit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\GruposController@postDelete');
    });

    Route::group(['prefix' => 'turmas'], function () {
        Route::get('/index/{id}', '\Modulos\Academico\Http\Controllers\TurmasController@getIndex');
        Route::get('/create/{id}', '\Modulos\Academico\Http\Controllers\TurmasController@getCreate');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\TurmasController@postCreate');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\TurmasController@getEdit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\TurmasController@putEdit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\TurmasController@postDelete');
    });

    Route::group(['prefix' => 'async'], function () {

        Route::group(['prefix' => 'matrizescurriculares'], function () {
            Route::get('/findallbycurso/{id}', '\Modulos\Academico\Http\Controllers\Async\MatrizesCurriculares@getFindallbycurso');
        });

        Route::group(['prefix' => 'turmas'], function () {
            Route::get('/findallbyofertacurso/{id}', '\Modulos\Academico\Http\Controllers\Async\Turmas@getFindallbyofertacurso');
        });

        Route::group(['prefix' => 'polos'], function () {
            Route::get('/findallbyofertacurso/{id}', '\Modulos\Academico\Http\Controllers\Async\Polos@getFindallbyofertacurso');
        });

        Route::group(['prefix' => 'ofertascursos'], function () {
            Route::get('/findallbycurso/{id}', '\Modulos\Academico\Http\Controllers\Async\OfertasCursos@getFindallbycurso');
        });
    });

});


