<?php

Route::group(['prefix' => 'monitoramento', 'middleware' => ['auth']], function () {
    Route::group(['prefix' => 'index'], function () {
        Route::get('/', '\Modulos\Monitoramento\Http\Controllers\IndexController@getIndex');
    });

    Route::group(['prefix' => 'tempoonline'], function () {
        Route::get('/index', '\Modulos\Monitoramento\Http\Controllers\TempoOnlineController@getIndex');
        Route::get('/monitorar/{id}', '\Modulos\Monitoramento\Http\Controllers\TempoOnlineController@getMonitorar');
    });
});
