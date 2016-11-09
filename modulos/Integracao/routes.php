<?php

Route::group(['prefix' => 'integracao', 'middleware' => ['auth']], function () {
    Route::group(['prefix' => 'index'], function () {
        Route::get('/', '\Modulos\Integracao\Http\Controllers\IndexController@getIndex')->name('academico.index.index');
        Route::get('/index', '\Modulos\Integracao\Http\Controllers\IndexController@getIndex')->name('academico.index.getIndex');
    });

    Route::group(['prefix' => 'ambientesvirtuais'], function () {
        Route::get('/index', '\Modulos\Integracao\Http\Controllers\AmbientesVirtuaisController@getIndex')->name('academico.ambientesvirtuais.index');
        Route::get('/create', '\Modulos\Integracao\Http\Controllers\AmbientesVirtuaisController@getCreate')->name('academico.ambientesvirtuais.getCreate');
        Route::post('/create', '\Modulos\Integracao\Http\Controllers\AmbientesVirtuaisController@postCreate')->name('academico.ambientesvirtuais.postCreate');
        Route::get('/edit/{id}', '\Modulos\Integracao\Http\Controllers\AmbientesVirtuaisController@getEdit')->name('academico.ambientesvirtuais.getEdit');
        Route::put('/edit/{id}', '\Modulos\Integracao\Http\Controllers\AmbientesVirtuaisController@putEdit')->name('academico.ambientesvirtuais.putEdit');
        Route::post('/delete', '\Modulos\Integracao\Http\Controllers\AmbientesVirtuaisController@postDelete')->name('academico.ambientesvirtuais.delete');
    });
});
