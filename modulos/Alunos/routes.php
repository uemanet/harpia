<?php

Route::group(['prefix' => 'alunos', 'middleware' => ['auth']], function () {
    Route::get('/index', '\Modulos\Alunos\Http\Controllers\IndexController@getIndex')->name('alunos.index.index');
    Route::get('/index/{id}', '\Modulos\Alunos\Http\Controllers\IndexController@getComprovanteMatricula')->name('alunos.comprovante.matricula');
});

Route::group(['prefix' => 'alunos'], function () {
    Route::get('/verifica-comprovante', '\Modulos\Alunos\Http\Controllers\IndexController@getVerificaComprovanteMatricula')->name('alunos.comprovante.verifica');
    Route::post('/verifica-comprovante', '\Modulos\Alunos\Http\Controllers\IndexController@postVerificaComprovanteMatricula')->name('alunos.comprovante.verifica');

});