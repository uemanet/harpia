<?php

Route::group(['prefix' => 'integracao', 'middleware' => ['auth']], function () {
    Route::get('/', '\Modulos\Integracao\Http\Controllers\IndexController@getIndex')->name('integracao.index.index');

    Route::group(['prefix' => 'ambientesvirtuais'], function () {
        Route::get('/', '\Modulos\Integracao\Http\Controllers\AmbientesVirtuaisController@getIndex')->name('integracao.ambientesvirtuais.index');
        Route::get('/create', '\Modulos\Integracao\Http\Controllers\AmbientesVirtuaisController@getCreate')->name('integracao.ambientesvirtuais.create');
        Route::post('/create', '\Modulos\Integracao\Http\Controllers\AmbientesVirtuaisController@postCreate')->name('integracao.ambientesvirtuais.create');
        Route::get('/edit/{id}', '\Modulos\Integracao\Http\Controllers\AmbientesVirtuaisController@getEdit')->name('integracao.ambientesvirtuais.edit');
        Route::put('/edit/{id}', '\Modulos\Integracao\Http\Controllers\AmbientesVirtuaisController@putEdit')->name('integracao.ambientesvirtuais.edit');
        Route::post('/delete', '\Modulos\Integracao\Http\Controllers\AmbientesVirtuaisController@postDelete')->name('integracao.ambientesvirtuais.delete');
        Route::get('/adicionarservico/{id}', '\Modulos\Integracao\Http\Controllers\AmbientesVirtuaisController@getAdicionarServico')->name('integracao.ambientesvirtuais.adicionarservico');
        Route::post('/adicionarservico/{id}', '\Modulos\Integracao\Http\Controllers\AmbientesVirtuaisController@postAdicionarServico')->name('integracao.ambientesvirtuais.adicionarservico');
        Route::post('/deletarservico/{id}', '\Modulos\Integracao\Http\Controllers\AmbientesVirtuaisController@postDeletarServico')->name('integracao.ambientesvirtuais.deletarservico');
        Route::get('/adicionarturma/{id}', '\Modulos\Integracao\Http\Controllers\AmbientesVirtuaisController@getAdicionarTurma')->name('integracao.ambientesvirtuais.adicionarturma');
        Route::post('/adicionarturma/{id}', '\Modulos\Integracao\Http\Controllers\AmbientesVirtuaisController@postAdicionarTurma')->name('integracao.ambientesvirtuais.adicionarturma');
        Route::post('/deletarturma', '\Modulos\Integracao\Http\Controllers\AmbientesVirtuaisController@postDeletarTurma')->name('integracao.ambientesvirtuais.deletarturma');
    });

    Route::group(['prefix' => 'mapeamentonotas'], function () {
        Route::get('/', '\Modulos\Integracao\Http\Controllers\MapeamentoNotas@index')->name('integracao.mapeamentonotas.index');
    });
});
