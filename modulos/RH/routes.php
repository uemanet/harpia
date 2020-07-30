<?php

Route::group(['prefix' => 'rh', 'middleware' => ['auth']], function () {
    Route::get('/', '\Modulos\RH\Http\Controllers\IndexController@getIndex')->name('rh.index.index');

    Route::group(['prefix' => 'areasconhecimentos'], function () {
        Route::get('/', '\Modulos\RH\Http\Controllers\AreasConhecimentosController@getIndex')->name('rh.areasconhecimentos.index');
        Route::get('/create', '\Modulos\RH\Http\Controllers\AreasConhecimentosController@getCreate')->name('rh.areasconhecimentos.create');
        Route::post('/create', '\Modulos\RH\Http\Controllers\AreasConhecimentosController@postCreate')->name('rh.areasconhecimentos.create');
        Route::get('/edit/{id}', '\Modulos\RH\Http\Controllers\AreasConhecimentosController@getEdit')->name('rh.areasconhecimentos.edit');
        Route::put('/edit/{id}', '\Modulos\RH\Http\Controllers\AreasConhecimentosController@putEdit')->name('rh.areasconhecimentos.edit');
        Route::post('/delete', '\Modulos\RH\Http\Controllers\AreasConhecimentosController@postDelete')->name('rh.areasconhecimentos.delete');
    });

    Route::group(['prefix' => 'bancos'], function () {
        Route::get('/', '\Modulos\RH\Http\Controllers\BancosController@getIndex')->name('rh.bancos.index');
        Route::get('/create', '\Modulos\RH\Http\Controllers\BancosController@getCreate')->name('rh.bancos.create');
        Route::post('/create', '\Modulos\RH\Http\Controllers\BancosController@postCreate')->name('rh.bancos.create');
        Route::get('/edit/{id}', '\Modulos\RH\Http\Controllers\BancosController@getEdit')->name('rh.bancos.edit');
        Route::put('/edit/{id}', '\Modulos\RH\Http\Controllers\BancosController@putEdit')->name('rh.bancos.edit');
        Route::post('/delete', '\Modulos\RH\Http\Controllers\BancosController@postDelete')->name('rh.bancos.delete');
    });

});
