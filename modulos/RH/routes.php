<?php

Route::group(['prefix' => 'api/rh'], function () {
    Route::group(['prefix' => 'colaboradores'], function () {
        Route::get('/show/{id}', '\Modulos\RH\Http\Controllers\ColaboradoresController@getShow')->name('rh.colaboradores.show');
    });

    // Monitor API — middleware auth.dispositivo
    Route::group(['prefix' => 'monitor', 'middleware' => ['auth.dispositivo']], function () {
        Route::post('/notifications/dao', '\Modulos\RH\Http\Controllers\MonitorController@receberLogAcesso')->name('rh.monitor.notifications.dao');
        Route::post('/notifications/{action}', '\Modulos\RH\Http\Controllers\MonitorController@receberNotificacao')->name('rh.monitor.notifications.action');
    });

    // Ponto remoto API — Fase 11 (sem middleware auth para permitir autenticacao por email/data_nascimento)
    Route::post('/ponto-remoto/entrada', '\Modulos\RH\Http\Controllers\PontoRemotoApiController@postEntrada')->name('rh.api.pontoremoto.entrada');
    Route::post('/ponto-remoto/saida', '\Modulos\RH\Http\Controllers\PontoRemotoApiController@postSaida')->name('rh.api.pontoremoto.saida');
});

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

    Route::group(['prefix' => 'dispositivos-acesso'], function () {
        Route::get('/', '\Modulos\RH\Http\Controllers\DispositivoAcessoController@getIndex')->name('rh.dispositivosacesso.index');
        Route::get('/create', '\Modulos\RH\Http\Controllers\DispositivoAcessoController@getCreate')->name('rh.dispositivosacesso.create');
        Route::post('/create', '\Modulos\RH\Http\Controllers\DispositivoAcessoController@postCreate')->name('rh.dispositivosacesso.create');
        Route::get('/edit/{id}', '\Modulos\RH\Http\Controllers\DispositivoAcessoController@getEdit')->name('rh.dispositivosacesso.edit');
        Route::put('/edit/{id}', '\Modulos\RH\Http\Controllers\DispositivoAcessoController@putEdit')->name('rh.dispositivosacesso.edit');
        Route::post('/delete', '\Modulos\RH\Http\Controllers\DispositivoAcessoController@postDelete')->name('rh.dispositivosacesso.delete');
        Route::post('/regenerar-token/{id}', '\Modulos\RH\Http\Controllers\DispositivoAcessoController@postRegenerarToken')->name('rh.dispositivosacesso.regenerartoken');
        Route::post('/ping/{id}', '\Modulos\RH\Http\Controllers\DispositivoAcessoController@postPing')->name('rh.dispositivosacesso.ping');
        Route::post('/sincronizar-mapeamento/{id}', '\Modulos\RH\Http\Controllers\DispositivoAcessoController@postSincronizarMapeamento')->name('rh.dispositivosacesso.sincronizarmapeamento');
    });

    Route::group(['prefix' => 'teste-dispositivo'], function () {
        Route::get('/', '\Modulos\RH\Http\Controllers\TesteDispositivoController@getIndex')->name('rh.testedispositivo.index');
        Route::post('/ping', '\Modulos\RH\Http\Controllers\TesteDispositivoController@postPing')->name('rh.testedispositivo.ping');
        Route::post('/listar-usuarios', '\Modulos\RH\Http\Controllers\TesteDispositivoController@postListarUsuarios')->name('rh.testedispositivo.listarusuarios');
        Route::post('/consultar-logs', '\Modulos\RH\Http\Controllers\TesteDispositivoController@postConsultarLogs')->name('rh.testedispositivo.consultarlogs');
        Route::get('/system-info', '\Modulos\RH\Http\Controllers\TesteDispositivoController@getSystemInfo')->name('rh.testedispositivo.systeminfo');
    });

    // Vincular colaboradores — Fase 5
    Route::group(['prefix' => 'vincular-colaboradores'], function () {
        Route::get('/', '\Modulos\RH\Http\Controllers\VincularColaboradoresController@getIndex')->name('rh.vincularcolaboradores.index');
        Route::post('/vincular', '\Modulos\RH\Http\Controllers\VincularColaboradoresController@postVincular')->name('rh.vincularcolaboradores.vincular');
        Route::post('/desvincular', '\Modulos\RH\Http\Controllers\VincularColaboradoresController@postDesvincular')->name('rh.vincularcolaboradores.desvincular');
        Route::post('/sincronizar', '\Modulos\RH\Http\Controllers\VincularColaboradoresController@postSincronizar')->name('rh.vincularcolaboradores.sincronizar');
        Route::get('/exportar-csv/{id}', '\Modulos\RH\Http\Controllers\VincularColaboradoresController@getExportarCsv')->name('rh.vincularcolaboradores.exportarcsv');
    });

    // Registros de ponto — Fase 8
    Route::group(['prefix' => 'registros-ponto'], function () {
        Route::get('/', '\Modulos\RH\Http\Controllers\RegistrosPontoController@getIndex')->name('rh.registrosponto.index');
        Route::get('/detalhes', '\Modulos\RH\Http\Controllers\RegistrosPontoController@getDetalhes')->name('rh.registrosponto.detalhes');
    });

    // Eventos de acesso — Fase 9
    Route::group(['prefix' => 'eventos-acesso'], function () {
        Route::get('/', '\Modulos\RH\Http\Controllers\EventoAcessoController@getIndex')->name('rh.eventosacesso.index');
        Route::get('/show/{id}', '\Modulos\RH\Http\Controllers\EventoAcessoController@getShow')->name('rh.eventosacesso.show');
    });

    // Ponto remoto — Fase 11
    Route::group(['prefix' => 'ponto-remoto'], function () {
        Route::get('/', '\Modulos\RH\Http\Controllers\PontoRemotoController@getIndex')->name('rh.pontoremoto.index');
        Route::post('/entrada', '\Modulos\RH\Http\Controllers\PontoRemotoController@postEntrada')->name('rh.pontoremoto.entrada');
        Route::post('/saida', '\Modulos\RH\Http\Controllers\PontoRemotoController@postSaida')->name('rh.pontoremoto.saida');
    });

    // Aprovacoes de ponto — Fase 12
    Route::group(['prefix' => 'aprovacoes-ponto'], function () {
        Route::get('/', '\Modulos\RH\Http\Controllers\AprovacaoPontoController@getIndex')->name('rh.aprovacoesponto.index');
        Route::get('/show/{id}', '\Modulos\RH\Http\Controllers\AprovacaoPontoController@getShow')->name('rh.aprovacoesponto.show');
        Route::post('/aprovar', '\Modulos\RH\Http\Controllers\AprovacaoPontoController@postAprovar')->name('rh.aprovacoesponto.aprovar');
        Route::post('/reprovar', '\Modulos\RH\Http\Controllers\AprovacaoPontoController@postReprovar')->name('rh.aprovacoesponto.reprovar');
    });

    // Configuracoes de ponto — Fase 13
    Route::group(['prefix' => 'configuracoes-ponto'], function () {
        Route::get('/', '\Modulos\RH\Http\Controllers\ConfiguracaoPontoController@getIndex')->name('rh.configuracoesponto.index');
        Route::post('/update', '\Modulos\RH\Http\Controllers\ConfiguracaoPontoController@postUpdate')->name('rh.configuracoesponto.update');
    });

    // Usuarios do dispositivo — Gestao Control iD
    Route::group(['prefix' => 'dispositivo-usuarios'], function () {
        Route::get('/', '\Modulos\RH\Http\Controllers\DispositivoUsuariosController@getIndex')->name('rh.dispositivousuarios.index');
        Route::post('/create', '\Modulos\RH\Http\Controllers\DispositivoUsuariosController@postCreate')->name('rh.dispositivousuarios.create');
        Route::get('/edit/{id}', '\Modulos\RH\Http\Controllers\DispositivoUsuariosController@getEdit')->name('rh.dispositivousuarios.edit');
        Route::put('/edit/{id}', '\Modulos\RH\Http\Controllers\DispositivoUsuariosController@putEdit')->name('rh.dispositivousuarios.edit');
        Route::post('/atualizar-foto/{id}', '\Modulos\RH\Http\Controllers\DispositivoUsuariosController@postAtualizarFoto')->name('rh.dispositivousuarios.atualizarfoto');
        Route::post('/remover-foto/{id}', '\Modulos\RH\Http\Controllers\DispositivoUsuariosController@postRemoverFoto')->name('rh.dispositivousuarios.removerfoto');
        Route::post('/delete', '\Modulos\RH\Http\Controllers\DispositivoUsuariosController@postDelete')->name('rh.dispositivousuarios.delete');
        Route::post('/sincronizar', '\Modulos\RH\Http\Controllers\DispositivoUsuariosController@postSincronizar')->name('rh.dispositivousuarios.sincronizar');
        Route::post('/sincronizar-todos', '\Modulos\RH\Http\Controllers\DispositivoUsuariosController@postSincronizarTodos')->name('rh.dispositivousuarios.sincronizartodos');
        Route::post('/sincronizar-entre-dispositivos', '\Modulos\RH\Http\Controllers\DispositivoUsuariosController@postSincronizarEntreDispositivos')->name('rh.dispositivousuarios.sincronizarentredispositivos');
        Route::get('/exportar-csv/{id?}', '\Modulos\RH\Http\Controllers\DispositivoUsuariosController@getExportarCsv')->name('rh.dispositivousuarios.exportarcsv');
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
