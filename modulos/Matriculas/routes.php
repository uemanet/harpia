<?php

Route::get('matriculas-alunos/', '\Modulos\Matriculas\Http\Controllers\Auth\AuthMatriculasController@logout')->name('auth.matriculas-alunos.alunos');
Route::get('matriculas-alunos/login', '\Modulos\Matriculas\Http\Controllers\Auth\AuthMatriculasController@showLoginForm')->name('auth.matriculas-alunos.login');
Route::post('matriculas-alunos/login', '\Modulos\Matriculas\Http\Controllers\Auth\AuthMatriculasController@postLogin')->name('auth.matriculas-alunos.login');
Route::get('matriculas-alunos/logout', '\Modulos\Matriculas\Http\Controllers\Auth\AuthMatriculasController@logout')->name('auth.matriculas-alunos.logout');

Route::group(['prefix' => 'matriculas', 'middleware' => ['auth']], function () {

    Route::get('/', '\Modulos\Matriculas\Http\Controllers\SeletivoController@getIndex')->name('matriculas.index.index');
    Route::get('/{id}/inscritos', '\Modulos\Matriculas\Http\Controllers\SeletivoController@getShow')->name('matriculas.index.inscricoes.index');
    Route::post('/chamada', '\Modulos\Matriculas\Http\Controllers\SeletivoController@createChamada')->name('matriculas.listachamadas.create');

    Route::post('/migracao', '\Modulos\Matriculas\Http\Controllers\SeletivoController@migracao')->name('matriculas.migracao.create');

    Route::group(['prefix' => 'chamadas'], function () {
        Route::get('/', '\Modulos\Matriculas\Http\Controllers\ChamadaController@getIndex')->name('matriculas.chamadas.index');
        Route::get('/create', '\Modulos\Matriculas\Http\Controllers\ChamadaController@getCreate')->name('matriculas.chamadas.create');
        Route::get('/{id}/candidatos', '\Modulos\Matriculas\Http\Controllers\ChamadaController@getMatriculas')->name('matriculas.chamadas.candidatos');
        Route::post('/create', '\Modulos\Matriculas\Http\Controllers\ChamadaController@postCreate')->name('matriculas.chamadas.create');
        Route::get('/edit/{id}', '\Modulos\Matriculas\Http\Controllers\ChamadaController@getEdit')->name('matriculas.chamadas.edit');
        Route::put('/edit/{id}', '\Modulos\Matriculas\Http\Controllers\ChamadaController@putEdit')->name('matriculas.chamadas.edit');
        Route::post('/delete', '\Modulos\Matriculas\Http\Controllers\ChamadaController@postDelete')->name('matriculas.chamadas.delete');

        Route::post('/matricular/{id}', '\Modulos\Matriculas\Http\Controllers\ChamadaController@postMatricular')->name('matriculas.chamadas.matricular');
        Route::post('/desmatricular/{id}', '\Modulos\Matriculas\Http\Controllers\ChamadaController@postDesmatricular')->name('matriculas.chamadas.desmatricular');

    });
});

Route::group(['prefix' => 'matriculas-alunos'], function () {

    Route::group(['prefix' => 'index'], function () {
        Route::get('/', '\Modulos\Matriculas\Http\Controllers\IndexController@getIndex')->name('matriculas-alunos.index.alunos');

    });

    Route::group(['prefix' => 'seletivo-matricula'], function () {

        Route::get('/{id}', '\Modulos\Matriculas\Http\Controllers\IndexController@getConfirmar')->name('matriculas-alunos.seletivo-matricula.confirmar');
        Route::post('/{id}', '\Modulos\Matriculas\Http\Controllers\IndexController@postConfirmar')->name('matriculas-alunos.seletivo-matricula.confirmar');
        Route::get('/{id}/comprovante', '\Modulos\Matriculas\Http\Controllers\IndexController@getComprovante')->name('matriculas-alunos.seletivo-matricula.comprovante');

    });
});

