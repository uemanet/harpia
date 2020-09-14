<?php

Route::group(['prefix' => 'matriculas', 'middleware' => ['auth']], function () {

    Route::get('/', '\Modulos\Matriculas\Http\Controllers\SeletivoController@getIndex')->name('matriculas.index.index');
    Route::get('/{id}/inscritos', '\Modulos\Matriculas\Http\Controllers\SeletivoController@getShow')->name('matriculas.index.inscricoes.index');
    Route::post('/chamada', '\Modulos\Matriculas\Http\Controllers\SeletivoController@createChamada')->name('matriculas.listachamadas.create');

    Route::group(['prefix' => 'chamadas'], function () {
        Route::get('/', '\Modulos\Matriculas\Http\Controllers\ChamadaController@getIndex')->name('matriculas.chamadas.index');
        Route::get('/create', '\Modulos\Matriculas\Http\Controllers\ChamadaController@getCreate')->name('matriculas.chamadas.create');
        Route::post('/create', '\Modulos\Matriculas\Http\Controllers\ChamadaController@postCreate')->name('matriculas.chamadas.create');
        Route::get('/edit/{id}', '\Modulos\Matriculas\Http\Controllers\ChamadaController@getEdit')->name('matriculas.chamadas.edit');
        Route::put('/edit/{id}', '\Modulos\Matriculas\Http\Controllers\ChamadaController@putEdit')->name('matriculas.chamadas.edit');
        Route::post('/delete', '\Modulos\Matriculas\Http\Controllers\ChamadaController@postDelete')->name('matriculas.chamadas.delete');
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