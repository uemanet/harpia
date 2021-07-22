<?php

Route::group(['prefix' => 'alunos', 'middleware' => ['auth']], function () {
    Route::get('/', '\Modulos\Alunos\Http\Controllers\IndexController@getIndex')->name('alunos.index.index');
});
