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

        Route::get('/status/{id}', '\Modulos\RH\Http\Controllers\ColaboradoresController@getStatus')->name('rh.colaboradores.status');
        Route::post('/matricula/{id}', '\Modulos\RH\Http\Controllers\ColaboradoresController@putMatricula')->name('rh.colaboradores.matricula');
        Route::get('/create-matricula/{id}', '\Modulos\RH\Http\Controllers\ColaboradoresController@getCreateMatricula')->name('rh.colaboradores.matricula.create');
        Route::post('/create-matricula/{id}', '\Modulos\RH\Http\Controllers\ColaboradoresController@createMatricula')->name('rh.colaboradores.matricula.create');
        Route::post('/delete-matricula', '\Modulos\RH\Http\Controllers\ColaboradoresController@postDeleteMatricula')->name('rh.colaboradores.matricula.delete-matricula');

        Route::get('/{id}/movimentacaosetor/', '\Modulos\RH\Http\Controllers\ColaboradoresController@getMovimentacaoSetor')->name('rh.colaboradores.movimentacaosetor.index');
        Route::post('/{id_coladorador}/movimentacaosetor/', '\Modulos\RH\Http\Controllers\ColaboradoresController@attachFuncao')->name('rh.colaboradores.movimentacaosetor.funcao.create');
        Route::post('/{id_coladorador}/movimentacaosetor/{id_colaborador_funcao}', '\Modulos\RH\Http\Controllers\ColaboradoresController@detachFuncao')->name('rh.colaboradores.movimentacaosetor.funcao.delete');
        Route::post('/{id_coladorador}/movimentacaosetor/{id_colaborador_funcao}/remove', '\Modulos\RH\Http\Controllers\ColaboradoresController@removeFuncao')->name('rh.colaboradores.movimentacaosetor.funcao.remove');

        Route::get('/show/{id}', '\Modulos\RH\Http\Controllers\ColaboradoresController@getShow')->name('rh.colaboradores.show');

        Route::get('{id}/horastrabalhadas/', '\Modulos\RH\Http\Controllers\HorasTrabalhadasController@getColaboradorHorasTrabalhadas')->name('rh.colaboradores.horastrabalhadas');
        Route::get('{id}/horastrabalhadasdiarias/{id_periodo_laboral}/periodo-laboral', '\Modulos\RH\Http\Controllers\HorasTrabalhadasDiariasController@getColaboradorHorasTrabalhadasDiariasPorPeriodoLaboral')->name('rh.horastrabalhadas.horastrabalhadasdiariasporperiodolaboral');

        Route::get('ferias/export', '\Modulos\RH\Http\Controllers\ColaboradoresController@exportFerias')->name('rh.ferias.export');

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

    Route::group(['prefix' => 'horastrabalhadas'], function () {
        Route::get('/', '\Modulos\RH\Http\Controllers\HorasTrabalhadasController@getIndex')->name('rh.horastrabalhadas.index');

        Route::group(['prefix' => 'justificativas'], function () {
            Route::get('/index/{id}', '\Modulos\RH\Http\Controllers\JustificativasController@getIndex')->name('rh.horastrabalhadas.justificativas.index');
            Route::get('/show/{id}', '\Modulos\RH\Http\Controllers\JustificativasController@getShow')->name('rh.horastrabalhadas.justificativas.show');
            Route::get('/anexo/{id}', '\Modulos\RH\Http\Controllers\JustificativasController@getAnexo')->name('rh.horastrabalhadas.justificativas.anexo');
            Route::get('/create', '\Modulos\RH\Http\Controllers\JustificativasController@getCreate')->name('rh.horastrabalhadas.justificativas.create');
            Route::post('/create', '\Modulos\RH\Http\Controllers\JustificativasController@postCreate')->name('rh.horastrabalhadas.justificativas.create');
            Route::get('/edit/{id}', '\Modulos\RH\Http\Controllers\JustificativasController@getEdit')->name('rh.horastrabalhadas.justificativas.edit');
            Route::put('/edit/{id}', '\Modulos\RH\Http\Controllers\JustificativasController@putEdit')->name('rh.horastrabalhadas.justificativas.edit');
            Route::post('/delete', '\Modulos\RH\Http\Controllers\JustificativasController@postDelete')->name('rh.horastrabalhadas.justificativas.delete');
        });

    });

    Route::group(['prefix' => 'horastrabalhadasdiarias'], function () {
        Route::post('/import', '\Modulos\RH\Http\Controllers\HorasTrabalhadasDiariasController@postImport')->name('rh.horastrabalhadasdiarias.import');
        Route::post('/pdf', '\Modulos\RH\Http\Controllers\HorasTrabalhadasDiariasController@postPdf')->name('rh.horastrabalhadasdiarias.pdf');
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

    Route::group(['prefix' => 'periodosaquisitivos'], function () {
        Route::get('/create/{id}', '\Modulos\RH\Http\Controllers\PeriodosAquisitivosController@getCreate')->name('rh.colaboradores.periodosaquisitivos.create');
        Route::post('/create/{id}', '\Modulos\RH\Http\Controllers\PeriodosAquisitivosController@postCreate')->name('rh.colaboradores.periodosaquisitivos.create');
        Route::get('/edit/{id}', '\Modulos\RH\Http\Controllers\PeriodosAquisitivosController@getEdit')->name('rh.colaboradores.periodosaquisitivos.edit');
        Route::put('/edit/{id}', '\Modulos\RH\Http\Controllers\PeriodosAquisitivosController@putEdit')->name('rh.colaboradores.periodosaquisitivos.edit');
        Route::post('/confirm/{id}', '\Modulos\RH\Http\Controllers\PeriodosAquisitivosController@putConfirm')->name('rh.colaboradores.periodosaquisitivos.confirm');
        Route::post('/delete', '\Modulos\RH\Http\Controllers\PeriodosAquisitivosController@postDelete')->name('rh.colaboradores.periodosaquisitivos.delete');
    });

    Route::group(['prefix' => 'periodosgozo'], function () {
        Route::get('/create/{id}', '\Modulos\RH\Http\Controllers\PeriodosGozoController@getCreate')->name('rh.colaboradores.periodosgozo.create');
        Route::post('/create/{id}', '\Modulos\RH\Http\Controllers\PeriodosGozoController@postCreate')->name('rh.colaboradores.periodosgozo.create');
        Route::get('/edit/{id}', '\Modulos\RH\Http\Controllers\PeriodosGozoController@getEdit')->name('rh.colaboradores.periodosgozo.edit');
        Route::put('/edit/{id}', '\Modulos\RH\Http\Controllers\PeriodosGozoController@putEdit')->name('rh.colaboradores.periodosgozo.edit');
        Route::post('/confirm/{id}', '\Modulos\RH\Http\Controllers\PeriodosGozoController@putConfirm')->name('rh.colaboradores.periodosgozo.confirm');
        Route::post('/delete', '\Modulos\RH\Http\Controllers\PeriodosGozoController@postDelete')->name('rh.colaboradores.periodosgozo.delete');
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
        Route::get('/edit/{id}', '\Modulos\RH\Http\Controllers\SalariosColaboradoresController@getEdit')->name('rh.colaboradores.salarioscolaboradores.edit');
        Route::put('/edit/{id}', '\Modulos\RH\Http\Controllers\SalariosColaboradoresController@putEdit')->name('rh.colaboradores.salarioscolaboradores.edit');
        Route::post('/delete', '\Modulos\RH\Http\Controllers\SalariosColaboradoresController@postDelete')->name('rh.colaboradores.salarioscolaboradores.delete');
    });

    Route::group(['prefix' => 'calendarios'], function () {
        Route::get('/', '\Modulos\RH\Http\Controllers\CalendariosController@getIndex')->name('rh.calendarios.index');
    });

    Route::group(['prefix' => 'relatorios'], function () {
        Route::get('/', '\Modulos\RH\Http\Controllers\RelatoriosPeriodosAquisitivosController@getIndex')->name('rh.relatorios.periodosaquisitivos');
    });

    //Rotas de funções assíncronas
    Route::group(['prefix' => 'async'], function () {
        Route::group(['prefix' => 'fontespagadoras'], function () {
            Route::get('/{id}/vinculosfontespagadoras', '\Modulos\RH\Http\Async\FontesPagadoras@getVinculosFontesPagadoras')->name('rh.async.fontespagadoras.vinculosfontespagadoras');
        });

        Route::group(['prefix' => 'calendarios'], function () {
            Route::get('/', '\Modulos\RH\Http\Async\Calendarios@index')->name('rh.async.calendarios.index');
            Route::post('/create', '\Modulos\RH\Http\Async\Calendarios@postCreate')->name('rh.async.calendarios.create');
            Route::get('/edit/{id}', '\Modulos\RH\Http\Async\Calendarios@getEdit')->name('rh.async.calendarios.edit');
            Route::put('/edit/{id}', '\Modulos\RH\Http\Async\Calendarios@putEdit')->name('rh.async.calendarios.edit');
            Route::post('/delete', '\Modulos\RH\Http\Async\Calendarios@postDelete')->name('rh.async.calendarios.delete');

        });
    });

});
