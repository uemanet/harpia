<?php

Route::group(['prefix' => 'integracao', 'middleware' => ['auth']], function () {
    Route::group(['prefix' => 'index'], function () {
        Route::get('/', '\Modulos\Integracao\Http\Controllers\IndexController@getIndex')->name('academico.index.index');
        Route::get('/index', '\Modulos\Integracao\Http\Controllers\IndexController@getIndex')->name('academico.index.getIndex');
    });

    Route::group(['prefix' => 'ambientesvirtuais'], function () {
        Route::get('/index', '\Modulos\Integracao\Http\Controllers\AmbientesVirtuaisController@getIndex')->name('integracao.ambientesvirtuais.index');
        Route::get('/create', '\Modulos\Integracao\Http\Controllers\AmbientesVirtuaisController@getCreate')->name('integracao.ambientesvirtuais.getCreate');
        Route::post('/create', '\Modulos\Integracao\Http\Controllers\AmbientesVirtuaisController@postCreate')->name('integracao.ambientesvirtuais.postCreate');
        Route::get('/edit/{id}', '\Modulos\Integracao\Http\Controllers\AmbientesVirtuaisController@getEdit')->name('integracao.ambientesvirtuais.getEdit');
        Route::put('/edit/{id}', '\Modulos\Integracao\Http\Controllers\AmbientesVirtuaisController@putEdit')->name('integracao.ambientesvirtuais.putEdit');
        Route::post('/delete', '\Modulos\Integracao\Http\Controllers\AmbientesVirtuaisController@postDelete')->name('integracao.ambientesvirtuais.delete');
        Route::get('/adicionarservico/{id}', '\Modulos\Integracao\Http\Controllers\AmbientesVirtuaisController@getAdicionarServico')->name('integracao.ambientesvirtuais.getAdicionarServico');
        Route::post('/adicionarservico/{id}', '\Modulos\Integracao\Http\Controllers\AmbientesVirtuaisController@postAdicionarServico')->name('integracao.ambientesvirtuais.postAdicionarServico');
        Route::post('/deletarservico/{id}', '\Modulos\Integracao\Http\Controllers\AmbientesVirtuaisController@postDeletarServico')->name('integracao.ambientesvirtuais.postDeletarServico');
        Route::get('/adicionarturma/{id}', '\Modulos\Integracao\Http\Controllers\AmbientesVirtuaisController@getAdicionarTurma')->name('integracao.ambientesvirtuais.getAdicionarTurma');
        Route::post('/adicionarturma/{id}', '\Modulos\Integracao\Http\Controllers\AmbientesVirtuaisController@postAdicionarTurma')->name('integracao.ambientesvirtuais.postAdicionarTurma');
    });
});
