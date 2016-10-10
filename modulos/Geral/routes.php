<?php

Route::group(['prefix' => 'geral', 'middleware' => ['auth']], function () {
    Route::group(['prefix' => 'index'], function () {
        Route::get('/', '\Modulos\Geral\Http\Controllers\IndexController@getIndex');        
    });
    
    Route::group(['prefix' => 'pessoas'], function () {
       Route::get('/verificapessoa', '\Modulos\Geral\Http\Controllers\PessoasController@getVerificaPessoa'); 
    });

    Route::group(['prefix' => 'async'], function () {
        Route::group(['prefix' => 'pessoas'], function () {
           //Route::get('/verificapessoa/{cpf}', '\Modulos\Geral\Http\Controllers\Async\Pessoas@getVerificapessoa');
        });
    }) ;
});
