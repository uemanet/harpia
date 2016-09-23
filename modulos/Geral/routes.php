<?php

Route::group(['prefix' => 'geral', 'middleware' => ['auth']], function () {

    Route::group(['prefix' => 'index'], function () {
       Route::get('/', '\Modulos\Geral\Http\Controllers\IndexController@getIndex');
    });

});
