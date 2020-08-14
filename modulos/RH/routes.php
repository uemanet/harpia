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

    Route::group(['prefix' => 'vinculos'], function () {
        Route::get('/', '\Modulos\RH\Http\Controllers\VinculosController@getIndex')->name('rh.vinculos.index');
        Route::get('/create', '\Modulos\RH\Http\Controllers\VinculosController@getCreate')->name('rh.vinculos.create');
        Route::post('/create', '\Modulos\RH\Http\Controllers\VinculosController@postCreate')->name('rh.vinculos.create');
        Route::get('/edit/{id}', '\Modulos\RH\Http\Controllers\VinculosController@getEdit')->name('rh.vinculos.edit');
        Route::put('/edit/{id}', '\Modulos\RH\Http\Controllers\VinculosController@putEdit')->name('rh.vinculos.edit');
        Route::post('/delete', '\Modulos\RH\Http\Controllers\VinculosController@postDelete')->name('rh.vinculos.delete');
    });


    Route::group(['prefix' => 'periodoslaborais'], function () {
        Route::get('/', '\Modulos\RH\Http\Controllers\PeriodosLaboraisController@getIndex')->name('rh.periodoslaborais.index');
        Route::get('/create', '\Modulos\RH\Http\Controllers\PeriodosLaboraisController@getCreate')->name('rh.periodoslaborais.create');
        Route::post('/create', '\Modulos\RH\Http\Controllers\PeriodosLaboraisController@postCreate')->name('rh.periodoslaborais.create');
        Route::get('/edit/{id}', '\Modulos\RH\Http\Controllers\PeriodosLaboraisController@getEdit')->name('rh.periodoslaborais.edit');
        Route::put('/edit/{id}', '\Modulos\RH\Http\Controllers\PeriodosLaboraisController@putEdit')->name('rh.periodoslaborais.edit');
        Route::post('/delete', '\Modulos\RH\Http\Controllers\PeriodosLaboraisController@postDelete')->name('rh.periodoslaborais.delete');
    });

    Route::group(['prefix' => 'colaboradores'], function () {
        Route::get('/', '\Modulos\RH\Http\Controllers\ColaboradoresController@getIndex')->name('rh.colaboradores.index');
        Route::get('/create', '\Modulos\RH\Http\Controllers\ColaboradoresController@getCreate')->name('rh.colaboradores.create')->middleware('verificapessoa');
        Route::post('/create', '\Modulos\RH\Http\Controllers\ColaboradoresController@postCreate')->name('rh.colaboradores.create');
        Route::get('/edit/{id}', '\Modulos\RH\Http\Controllers\ColaboradoresController@getEdit')->name('rh.colaboradores.edit');
        Route::put('/edit/{id}', '\Modulos\RH\Http\Controllers\ColaboradoresController@putEdit')->name('rh.colaboradores.edit');
        Route::get('/show/{id}', '\Modulos\RH\Http\Controllers\ColaboradoresController@getShow')->name('rh.colaboradores.show');
    });

    Route::group(['prefix' => 'funcoes'], function () {
        Route::get('/', '\Modulos\RH\Http\Controllers\FuncoesController@getIndex')->name('rh.funcoes.index');
        Route::get('/create', '\Modulos\RH\Http\Controllers\FuncoesController@getCreate')->name('rh.funcoes.create');
        Route::post('/create', '\Modulos\RH\Http\Controllers\FuncoesController@postCreate')->name('rh.funcoes.create');
        Route::get('/edit/{id}', '\Modulos\RH\Http\Controllers\FuncoesController@getEdit')->name('rh.funcoes.edit');
        Route::put('/edit/{id}', '\Modulos\RH\Http\Controllers\FuncoesController@putEdit')->name('rh.funcoes.edit');
        Route::post('/delete', '\Modulos\RH\Http\Controllers\FuncoesController@postDelete')->name('rh.funcoes.delete');
    });

    Route::group(['prefix' => 'setores'], function () {
        Route::get('/', '\Modulos\RH\Http\Controllers\SetoresController@getIndex')->name('rh.setores.index');
        Route::get('/create', '\Modulos\RH\Http\Controllers\SetoresController@getCreate')->name('rh.setores.create');
        Route::post('/create', '\Modulos\RH\Http\Controllers\SetoresController@postCreate')->name('rh.setores.create');
        Route::get('/edit/{id}', '\Modulos\RH\Http\Controllers\SetoresController@getEdit')->name('rh.setores.edit');
        Route::put('/edit/{id}', '\Modulos\RH\Http\Controllers\SetoresController@putEdit')->name('rh.setores.edit');
        Route::post('/delete', '\Modulos\RH\Http\Controllers\SetoresController@postDelete')->name('rh.setores.delete');

    });

    Route::group(['prefix' => 'fontespagadoras'], function () {
        Route::get('/', '\Modulos\RH\Http\Controllers\FontesPagadorasController@getIndex')->name('rh.fontespagadoras.index');
        Route::get('/show/{id}', '\Modulos\RH\Http\Controllers\FontesPagadorasController@getShow')->name('rh.fontespagadoras.show');
        Route::get('/create', '\Modulos\RH\Http\Controllers\FontesPagadorasController@getCreate')->name('rh.fontespagadoras.create');
        Route::post('/create', '\Modulos\RH\Http\Controllers\FontesPagadorasController@postCreate')->name('rh.fontespagadoras.create');
        Route::get('/edit/{id}', '\Modulos\RH\Http\Controllers\FontesPagadorasController@getEdit')->name('rh.fontespagadoras.edit');
        Route::put('/edit/{id}', '\Modulos\RH\Http\Controllers\FontesPagadorasController@putEdit')->name('rh.fontespagadoras.edit');
        Route::post('/delete', '\Modulos\RH\Http\Controllers\FontesPagadorasController@postDelete')->name('rh.fontespagadoras.delete');
    });

    Route::group(['prefix' => 'atividadesextrascolaboradores'], function () {
        Route::get('/create/{id}', '\Modulos\RH\Http\Controllers\AtividadeExtraColaboradorController@getCreate')->name('rh.colaboradores.atividadesextrascolaboradores.create');
        Route::post('/create/{id}', '\Modulos\RH\Http\Controllers\AtividadeExtraColaboradorController@postCreate')->name('rh.colaboradores.atividadesextrascolaboradores.create');
        Route::get('/edit/{id}', '\Modulos\RH\Http\Controllers\AtividadeExtraColaboradorController@getEdit')->name('rh.colaboradores.atividadesextrascolaboradores.edit');
        Route::put('/edit/{id}', '\Modulos\RH\Http\Controllers\AtividadeExtraColaboradorController@putEdit')->name('rh.colaboradores.atividadesextrascolaboradores.edit');
        Route::post('/delete', '\Modulos\RH\Http\Controllers\AtividadeExtraColaboradorController@postDelete')->name('rh.colaboradores.atividadesextrascolaboradores.delete');
    });

    Route::group(['prefix' => 'contascolaboradores'], function () {
        Route::get('/create/{id}', '\Modulos\RH\Http\Controllers\ContasColaboradoresController@getCreate')->name('rh.colaboradores.contascolaboradores.create');
        Route::post('/create/{id}', '\Modulos\RH\Http\Controllers\ContasColaboradoresController@postCreate')->name('rh.colaboradores.contascolaboradores.create');
        Route::get('/edit/{id}', '\Modulos\RH\Http\Controllers\ContasColaboradoresController@getEdit')->name('rh.colaboradores.contascolaboradores.edit');
        Route::put('/edit/{id}', '\Modulos\RH\Http\Controllers\ContasColaboradoresController@putEdit')->name('rh.colaboradores.contascolaboradores.edit');
        Route::post('/delete', '\Modulos\RH\Http\Controllers\ContasColaboradoresController@postDelete')->name('rh.colaboradores.contascolaboradores.delete');
    });

    Route::group(['prefix' => 'vinculosfontespagadoras'], function () {
        Route::get('/create/{id}', '\Modulos\RH\Http\Controllers\VinculosFontesPagadorasController@getCreate')->name('rh.fontespagadoras.vinculosfontespagadoras.create');
        Route::post('/create/{id}', '\Modulos\RH\Http\Controllers\VinculosFontesPagadorasController@postCreate')->name('rh.fontespagadoras.vinculosfontespagadoras.create');
        Route::get('/edit/{id}', '\Modulos\RH\Http\Controllers\VinculosFontesPagadorasController@getEdit')->name('rh.fontespagadoras.vinculosfontespagadoras.edit');
        Route::put('/edit/{id}', '\Modulos\RH\Http\Controllers\VinculosFontesPagadorasController@putEdit')->name('rh.fontespagadoras.vinculosfontespagadoras.edit');
        Route::post('/delete', '\Modulos\RH\Http\Controllers\VinculosFontesPagadorasController@postDelete')->name('rh.fontespagadoras.vinculosfontespagadoras.delete');
    });

    Route::group(['prefix' => 'salarioscolaboradores'], function () {
        Route::get('/create/{id}', '\Modulos\RH\Http\Controllers\SalariosColaboradoresController@getCreate')->name('rh.colaboradores.salarioscolaboradores.create');
        Route::post('/create/{id}', '\Modulos\RH\Http\Controllers\SalariosColaboradoresController@postCreate')->name('rh.colaboradores.salarioscolaboradores.create');
    });
});
