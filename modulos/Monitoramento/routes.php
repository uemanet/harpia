<?php

Route::group(['prefix' => 'monitoramento', 'middleware' => ['auth']], function () {
    Route::group(['prefix' => 'index'], function () {
        Route::get('/', '\Modulos\Monitoramento\Http\Controllers\IndexController@getIndex');
    });
});
