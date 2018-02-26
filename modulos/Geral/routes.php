<?php

Route::group(['prefix' => 'geral', 'middleware' => ['auth']], function () {
    Route::get('/', '\Modulos\Geral\Http\Controllers\IndexController@getIndex')->name('geral.index.index');

    Route::group(['prefix' => 'pessoas'], function () {
        Route::get('/', '\Modulos\Geral\Http\Controllers\PessoasController@getIndex')->name('geral.pessoas.index');
        Route::get('/create', '\Modulos\Geral\Http\Controllers\PessoasController@getCreate')->name('geral.pessoas.create');
        Route::post('/create', '\Modulos\Geral\Http\Controllers\PessoasController@postCreate')->name('geral.pessoas.create');
        Route::get('/edit/{id}', '\Modulos\Geral\Http\Controllers\PessoasController@getEdit')->name('geral.pessoas.edit');
        Route::put('/edit/{id}', '\Modulos\Geral\Http\Controllers\PessoasController@putEdit')->name('geral.pessoas.edit');
        Route::get('/show/{id}', '\Modulos\Geral\Http\Controllers\PessoasController@getShow')->name('geral.pessoas.show');
        Route::get('/verificapessoa/{rota}', '\Modulos\Geral\Http\Controllers\PessoasController@getVerificapessoa')->name('geral.pessoas.verificapessoa');
        Route::post('/verificapessoa', '\Modulos\Geral\Http\Controllers\PessoasController@postVerificapessoa')->name('geral.pessoas.verificapessoa');
    });


    Route::group(['prefix' => 'documentos'], function () {
        Route::get('/create/{id}', '\Modulos\Geral\Http\Controllers\DocumentosController@getCreate')->name('geral.pessoas.documentos.create');
        Route::post('/create/{id}', '\Modulos\Geral\Http\Controllers\DocumentosController@postCreate')->name('geral.pessoas.documentos.create');
        Route::get('/edit/{id}', '\Modulos\Geral\Http\Controllers\DocumentosController@getEdit')->name('geral.pessoas.documentos.edit');
        Route::put('/edit/{id}', '\Modulos\Geral\Http\Controllers\DocumentosController@putEdit')->name('geral.pessoas.documentos.edit');
        Route::post('/delete', '\Modulos\Geral\Http\Controllers\DocumentosController@postDelete')->name('geral.pessoas.documentos.delete');
        Route::post('/deleteAnexo', '\Modulos\Geral\Http\Controllers\DocumentosController@postDeleteAnexo')->name('geral.pessoas.documentos.deleteanexo');
        Route::get('/anexo/{id}', '\Modulos\Geral\Http\Controllers\DocumentosController@getDocumentoAnexo')->name('geral.pessoas.documentos.anexo');
    });


    Route::group(['prefix' => 'titulacoes'], function () {
        Route::get('/', '\Modulos\Geral\Http\Controllers\TitulacoesController@getIndex')->name('geral.titulacoes.index');
        Route::get('/create', '\Modulos\Geral\Http\Controllers\TitulacoesController@getCreate')->name('geral.titulacoes.create');
        Route::post('/create', '\Modulos\Geral\Http\Controllers\TitulacoesController@postCreate')->name('geral.titulacoes.create');
        Route::get('/edit/{id}', '\Modulos\Geral\Http\Controllers\TitulacoesController@getEdit')->name('geral.titulacoes.edit');
        Route::put('/edit/{id}', '\Modulos\Geral\Http\Controllers\TitulacoesController@putEdit')->name('geral.titulacoes.edit');
        Route::post('/delete', '\Modulos\Geral\Http\Controllers\TitulacoesController@postDelete')->name('geral.titulacoes.delete');
    });

    Route::group(['prefix' => 'titulacoesinformacoes'], function () {
        Route::get('/create/{id}', '\Modulos\Geral\Http\Controllers\TitulacoesInformacoesController@getCreate')->name('geral.pessoas.titulacoesinformacoes.create');
        Route::post('/create/{id}', '\Modulos\Geral\Http\Controllers\TitulacoesInformacoesController@postCreate')->name('geral.pessoas.titulacoesinformacoes.create');
        Route::get('/edit/{id}', '\Modulos\Geral\Http\Controllers\TitulacoesInformacoesController@getEdit')->name('geral.pessoas.titulacoesinformacoes.edit');
        Route::put('/edit/{id}', '\Modulos\Geral\Http\Controllers\TitulacoesInformacoesController@putEdit')->name('geral.pessoas.titulacoesinformacoes.edit');
        Route::post('/delete', '\Modulos\Geral\Http\Controllers\TitulacoesInformacoesController@postDelete')->name('geral.pessoas.titulacoesinformacoes.delete');
    });

    Route::group(['prefix' => 'async'], function () {
        Route::group(['prefix' => 'anexos'], function () {
            Route::post('/deletaranexodocumento', '\Modulos\Geral\Http\Controllers\Async\Documentos@postDeletarAnexo')->name('geral.async.anexos.deletaranexodocumento');
        });
    });
});
