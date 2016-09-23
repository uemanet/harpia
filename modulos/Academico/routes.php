<?php

Route::group(['prefix' => 'academico', 'middleware' => ['auth']], function () {

    Route::group(['prefix' => 'index'], function () {
        Route::get('/', '\Modulos\Academico\Http\Controllers\indexController@getIndex')->name('acd.index.index');
        Route::get('/index', '\Modulos\Academico\Http\Controllers\indexController@getIndex')->name('acd.index.getIndex');
    });

    Route::group(['prefix' => 'polos'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\PolosController@getIndex')->name('acd.polos.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\PolosController@getCreate')->name('acd.polos.getCreate');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\PolosController@postCreate')->name('acd.polos.postCreate');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\PolosController@getEdit')->name('acd.polos.getEdit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\PolosController@putEdit')->name('acd.polos.putEdit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\PolosController@postDelete')->name('acd.polos.delete');
    });

    Route::group(['prefix' => 'departamentos'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\DepartamentosController@getIndex')->name('acd.departamentos.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\DepartamentosController@getCreate')->name('acd.departamentos.getCreate');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\DepartamentosController@postCreate')->name('acd.departamentos.postCreate');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\DepartamentosController@getEdit')->name('acd.departamentos.getEdit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\DepartamentosController@putEdit')->name('acd.departamentos.putEdit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\DepartamentosController@postDelete')->name('acd.departamentos.delete');
    });

    Route::group(['prefix' => 'periodosletivos'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\PeriodosLetivosController@getIndex')->name('acd.periodosletivos.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\PeriodosLetivosController@getCreate')->name('acd.periodosletivos.getCreate');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\PeriodosLetivosController@postCreate')->name('acd.periodosletivos.postCreate');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\PeriodosLetivosController@getEdit')->name('acd.periodosletivos.getEdit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\PeriodosLetivosController@putEdit')->name('acd.periodosletivos.putEdit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\PeriodosLetivosController@postDelete')->name('acd.periodosletivos.delete');
    });

    Route::group(['prefix' => 'cursos'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\CursosController@getIndex')->name('acd.cursos.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\CursosController@getCreate')->name('acd.cursos.getCreate');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\CursosController@postCreate')->name('acd.cursos.postCreate');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\CursosController@getEdit')->name('acd.cursos.getEdit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\CursosController@putEdit')->name('acd.cursos.putEdit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\CursosController@postDelete')->name('acd.cursos.delete');
    });

    Route::group(['prefix' => 'matrizescurriculares'], function () {
        Route::get('/index/{id}', '\Modulos\Academico\Http\Controllers\MatrizesCurricularesController@getIndex')->name('acd.matrizescurriculares.index');
        Route::get('/create/{id}', '\Modulos\Academico\Http\Controllers\MatrizesCurricularesController@getCreate')->name('acd.matrizescurriculares.getCreate');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\MatrizesCurricularesController@postCreate')->name('acd.matrizescurriculares.postCreate');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\MatrizesCurricularesController@getEdit')->name('acd.matrizescurriculares.getEdit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\MatrizesCurricularesController@putEdit')->name('acd.matrizescurriculares.putEdit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\MatrizesCurricularesController@postDelete')->name('acd.matrizescurriculares.delete');
    });

    Route::group(['prefix' => 'centros'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\CentrosController@getIndex')->name('acd.centros.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\CentrosController@getCreate')->name('acd.centros.getCreate');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\CentrosController@postCreate')->name('acd.centros.postCreate');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\CentrosController@getEdit')->name('acd.centros.getEdit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\CentrosController@putEdit')->name('acd.centros.putEdit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\CentrosController@postDelete')->name('acd.centros.delete');
    });

    Route::group(['prefix' => 'ofertascursos'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\OfertasCursosController@getIndex')->name('acd.ofertascursos.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\OfertasCursosController@getCreate')->name('acd.ofertascursos.getCreate');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\OfertasCursosController@postCreate')->name('acd.ofertascursos.postCreate');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\OfertasCursosController@postDelete')->name('acd.ofertascursos.delete');
    });

    Route::group(['prefix' => 'grupos'], function () {
        Route::get('/index/{id}', '\Modulos\Academico\Http\Controllers\GruposController@getIndex')->name('acd.grupos.index');
        Route::get('/create/{id}', '\Modulos\Academico\Http\Controllers\GruposController@getCreate')->name('acd.grupos.getCreate');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\GruposController@postCreate')->name('acd.grupos.postCreate');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\GruposController@getEdit')->name('acd.grupos.getEdit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\GruposController@putEdit')->name('acd.grupos.putEdit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\GruposController@postDelete')->name('acd.grupos.delete');
    });

    Route::group(['prefix' => 'turmas'], function () {
        Route::get('/index/{id}', '\Modulos\Academico\Http\Controllers\TurmasController@getIndex')->name('acd.turmas.index');
        Route::get('/create/{id}', '\Modulos\Academico\Http\Controllers\TurmasController@getCreate')->name('acd.turmas.getCreate');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\TurmasController@postCreate')->name('acd.turmas.postCreate');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\TurmasController@getEdit')->name('acd.turmas.getEdit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\TurmasController@putEdit')->name('acd.turmas.putEdit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\TurmasController@postDelete')->name('acd.turmas.delete');
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


