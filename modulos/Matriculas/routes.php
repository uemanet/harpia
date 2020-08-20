<?php

Route::group(['prefix' => 'matriculas', 'middleware' => ['auth']], function () {

    Route::get('/', '\Modulos\Matriculas\Http\Controllers\IndexController@getIndex')->name('matriculas.index.index');

});
