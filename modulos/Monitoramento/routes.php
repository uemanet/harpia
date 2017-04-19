<?php

Route::group(['prefix' => 'monitoramento', 'middleware' => ['auth']], function () {
    Route::group(['prefix' => 'index'], function () {
        Route::get('/', '\Modulos\Monitoramento\Http\Controllers\IndexController@getIndex')->name('monitoramento.index.index');
        Route::get('/index', '\Modulos\Monitoramento\Http\Controllers\IndexController@getIndex')->name('monitoramento.index.index');
    });

    Route::group(['prefix' => 'tempoonline'], function () {
        Route::get('/index', '\Modulos\Monitoramento\Http\Controllers\TempoOnlineController@getIndex')->name('monitoramento.tempoonline.index');
        Route::get('/monitorar/{id}', '\Modulos\Monitoramento\Http\Controllers\TempoOnlineController@getMonitorar')->name('monitoramento.tempoonline.monitorar');
    });
});
