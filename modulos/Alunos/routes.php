<?php

Route::group(['prefix' => 'alunos', 'middleware' => ['auth']], function () {
    Route::get('/', '\Modulos\Alunos\Http\Controllers\IndexController@getIndex')->name('alunos.index.index');
    Route::get('/{id}', '\Modulos\Alunos\Http\Controllers\IndexController@getComprovanteMatricula')->name('alunos.comprovante.matricula');
});
