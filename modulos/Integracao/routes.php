<?php

Route::group(['prefix' => 'integracao', 'middleware' => ['auth']], function () {
    Route::group(['prefix' => 'index'], function () {
        Route::get('/', '\Modulos\Integracao\Http\Controllers\IndexController@getIndex')->name('academico.index.index');
        Route::get('/index', '\Modulos\Integracao\Http\Controllers\IndexController@getIndex')->name('academico.index.getIndex');
    });


});
