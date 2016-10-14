<?php

Route::group(['prefix' => 'geral', 'middleware' => ['auth']], function () {
    Route::group(['prefix' => 'index'], function () {
        Route::get('/', '\Modulos\Geral\Http\Controllers\IndexController@getIndex');        
    });
    
    Route::group(['prefix' => 'pessoas'], function () {
        Route::get('/index', '\Modulos\Geral\Http\Controllers\PessoasController@getIndex')->name('geral.pessoas.index');
        Route::get('/create', '\Modulos\Geral\Http\Controllers\PessoasController@getCreate')->name('geral.pessoas.getCreate');
        Route::post('/create', '\Modulos\Geral\Http\Controllers\PessoasController@postCreate')->name('geral.pessoas.postCreate');
        Route::get('/edit/{id}', '\Modulos\Geral\Http\Controllers\PessoasController@getEdit')->name('geral.pessoas.getEdit');
        Route::put('/edit/{id}', '\Modulos\Geral\Http\Controllers\PessoasController@putEdit')->name('geral.pessoas.putEdit');
        Route::get('/verificapessoa', '\Modulos\Geral\Http\Controllers\PessoasController@getVerificaPessoa');
    });

    Route::group(['prefix' => 'async'], function () {
        Route::group(['prefix' => 'pessoas'], function () {
           //Route::get('/verificapessoa/{cpf}', '\Modulos\Geral\Http\Controllers\Async\Pessoas@getVerificapessoa');
        });
    }) ;
});
