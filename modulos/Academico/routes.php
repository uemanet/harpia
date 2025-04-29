<?php

Route::group(['prefix' => 'academico', 'middleware' => ['auth']], function () {
    Route::get('/', '\Modulos\Academico\Http\Controllers\indexController@getIndex')->name('academico.index.index');

    Route::group(['prefix' => 'importacoes'], function () {
        Route::get('/', '\Modulos\Academico\Http\Controllers\ImportacoesController@getIndex')->name('academico.importacoes.index');
        Route::post('/importar', '\Modulos\Academico\Http\Controllers\ImportacoesController@postImportar')->name('academico.importacoes.importar');
    });

    Route::group(['prefix' => 'importacoesusuarios'], function () {
        Route::get('/', '\Modulos\Academico\Http\Controllers\ImportacoesUsuariosController@getIndex')->name('academico.importacoesusuarios.index');
        Route::post('/importar', '\Modulos\Academico\Http\Controllers\ImportacoesUsuariosController@postImportar')->name('academico.importacoesusuarios.importar');
    });

    Route::group(['prefix' => 'instituicoes'], function () {
        Route::get('/', '\Modulos\Academico\Http\Controllers\InstituicoesController@getIndex')->name('academico.instituicoes.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\InstituicoesController@getCreate')->name('academico.instituicoes.create');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\InstituicoesController@postCreate')->name('academico.instituicoes.create');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\InstituicoesController@getEdit')->name('academico.instituicoes.edit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\InstituicoesController@putEdit')->name('academico.instituicoes.edit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\InstituicoesController@postDelete')->name('academico.instituicoes.delete');

        Route::get('/{id}/turmas', '\Modulos\Academico\Http\Controllers\InstituicoesController@getTurmas')->name('academico.instituicoes.turmas');
        Route::post('/{id}/turmas', '\Modulos\Academico\Http\Controllers\InstituicoesController@postTurmas')->name('academico.instituicoes.turmas');

        Route::get('/{id}/pessoas', '\Modulos\Academico\Http\Controllers\InstituicoesController@getPessoas')->name('academico.instituicoes.pessoas');
        Route::post('/{id}/pessoas', '\Modulos\Academico\Http\Controllers\InstituicoesController@postPessoas')->name('academico.instituicoes.pessoas');
    });

    Route::group(['prefix' => 'noticias'], function () {
        Route::get('/', '\Modulos\Academico\Http\Controllers\NoticiasController@getIndex')->name('academico.noticias.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\NoticiasController@getCreate')->name('academico.noticias.create');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\NoticiasController@postCreate')->name('academico.noticias.create');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\NoticiasController@getEdit')->name('academico.noticias.edit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\NoticiasController@putEdit')->name('academico.noticias.edit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\NoticiasController@postDelete')->name('academico.noticias.delete');
    });

    Route::group(['prefix' => 'polos'], function () {
        Route::get('/', '\Modulos\Academico\Http\Controllers\PolosController@getIndex')->name('academico.polos.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\PolosController@getCreate')->name('academico.polos.create');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\PolosController@postCreate')->name('academico.polos.create');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\PolosController@getEdit')->name('academico.polos.edit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\PolosController@putEdit')->name('academico.polos.edit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\PolosController@postDelete')->name('academico.polos.delete');
    });

    Route::group(['prefix' => 'centros'], function () {
        Route::get('/', '\Modulos\Academico\Http\Controllers\CentrosController@getIndex')->name('academico.centros.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\CentrosController@getCreate')->name('academico.centros.create');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\CentrosController@postCreate')->name('academico.centros.create');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\CentrosController@getEdit')->name('academico.centros.edit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\CentrosController@putEdit')->name('academico.centros.edit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\CentrosController@postDelete')->name('academico.centros.delete');
    });

    Route::group(['prefix' => 'departamentos'], function () {
        Route::get('/', '\Modulos\Academico\Http\Controllers\DepartamentosController@getIndex')->name('academico.departamentos.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\DepartamentosController@getCreate')->name('academico.departamentos.create');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\DepartamentosController@postCreate')->name('academico.departamentos.create');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\DepartamentosController@getEdit')->name('academico.departamentos.edit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\DepartamentosController@putEdit')->name('academico.departamentos.edit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\DepartamentosController@postDelete')->name('academico.departamentos.delete');
    });

    Route::group(['prefix' => 'alunos', 'middleware' => ['vinculo_turmas']], function () {
        Route::get('/', '\Modulos\Academico\Http\Controllers\AlunosController@getIndex')->name('academico.alunos.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\AlunosController@getCreate')->name('academico.alunos.create')->middleware('verificapessoa');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\AlunosController@postCreate')->name('academico.alunos.create');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\AlunosController@getEdit')->name('academico.alunos.edit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\AlunosController@putEdit')->name('academico.alunos.edit');
        Route::get('/show/{id}', '\Modulos\Academico\Http\Controllers\AlunosController@getShow')->name('academico.alunos.show');
    });

    Route::group(['prefix' => 'professores'], function () {
        Route::get('/', '\Modulos\Academico\Http\Controllers\ProfessoresController@getIndex')->name('academico.professores.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\ProfessoresController@getCreate')->name('academico.professores.create')->middleware('verificapessoa');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\ProfessoresController@postCreate')->name('academico.professores.create');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\ProfessoresController@getEdit')->name('academico.professores.edit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\ProfessoresController@putEdit')->name('academico.professores.edit');
        Route::get('/show/{id}', '\Modulos\Academico\Http\Controllers\ProfessoresController@getShow')->name('academico.professores.show');
    });

    Route::group(['prefix' => 'tutores'], function () {
        Route::get('/', '\Modulos\Academico\Http\Controllers\TutoresController@getIndex')->name('academico.tutores.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\TutoresController@getCreate')->name('academico.tutores.create')->middleware('verificapessoa');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\TutoresController@postCreate')->name('academico.tutores.create');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\TutoresController@getEdit')->name('academico.tutores.edit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\TutoresController@putEdit')->name('academico.tutores.edit');
        Route::get('/show/{id}', '\Modulos\Academico\Http\Controllers\TutoresController@getShow')->name('academico.tutores.show');
    });

    Route::group(['prefix' => 'usuarioscursos'], function () {
        Route::get('/', '\Modulos\Academico\Http\Controllers\VinculosController@getIndex')->name('academico.vinculos.index');
        Route::get('/vinculos/{id}', '\Modulos\Academico\Http\Controllers\VinculosController@getVinculos')->name('academico.vinculos.vinculos');
        Route::get('/create/{id}', '\Modulos\Academico\Http\Controllers\VinculosController@getCreate')->name('academico.vinculos.create');
        Route::post('/create/{id}', '\Modulos\Academico\Http\Controllers\VinculosController@postCreate')->name('academico.vinculos.create');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\VinculosController@postDelete')->name('academico.vinculos.delete');
    });

    Route::group(['prefix' => 'cursos'], function () {
        Route::get('/', '\Modulos\Academico\Http\Controllers\CursosController@getIndex')->name('academico.cursos.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\CursosController@getCreate')->name('academico.cursos.create');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\CursosController@postCreate')->name('academico.cursos.create');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\CursosController@getEdit')->name('academico.cursos.edit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\CursosController@putEdit')->name('academico.cursos.edit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\CursosController@postDelete')->name('academico.cursos.delete');
    });

    Route::group(['prefix' => 'matrizescurriculares'], function () {
        Route::get('/index/{id}', '\Modulos\Academico\Http\Controllers\MatrizesCurricularesController@getIndex')->name('academico.cursos.matrizescurriculares.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\MatrizesCurricularesController@getCreate')->name('academico.cursos.matrizescurriculares.create');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\MatrizesCurricularesController@postCreate')->name('academico.cursos.matrizescurriculares.create');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\MatrizesCurricularesController@getEdit')->name('academico.cursos.matrizescurriculares.edit');
        Route::get('/anexo/{id}', '\Modulos\Academico\Http\Controllers\MatrizesCurricularesController@getMatrizAnexo')->name('academico.cursos.matrizescurriculares.anexo');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\MatrizesCurricularesController@putEdit')->name('academico.cursos.matrizescurriculares.edit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\MatrizesCurricularesController@postDelete')->name('academico.cursos.matrizescurriculares.delete');
    });

    Route::group(['prefix' => 'modulosmatrizes'], function () {
        Route::get('/index/{id}', '\Modulos\Academico\Http\Controllers\ModulosMatrizesController@getIndex')->name('academico.cursos.matrizescurriculares.modulosmatrizes.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\ModulosMatrizesController@getCreate')->name('academico.cursos.matrizescurriculares.modulosmatrizes.create');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\ModulosMatrizesController@postCreate')->name('academico.cursos.matrizescurriculares.modulosmatrizes.create');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\ModulosMatrizesController@getEdit')->name('academico.cursos.matrizescurriculares.modulosmatrizes.edit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\ModulosMatrizesController@putEdit')->name('academico.cursos.matrizescurriculares.modulosmatrizes.edit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\ModulosMatrizesController@postDelete')->name('academico.cursos.matrizescurriculares.modulosmatrizes.delete');
        Route::get('/gerenciardisciplinas/{id}', '\Modulos\Academico\Http\Controllers\ModulosMatrizesController@getGerenciarDisciplinas')->name('academico.cursos.matrizescurriculares.modulosmatrizes.gerenciardisciplinas');
        Route::get('/editardisciplina/{id}', '\Modulos\Academico\Http\Controllers\ModulosMatrizesController@getEditarDisciplinas')->name('academico.cursos.matrizescurriculares.modulosmatrizes.editardisciplinas');
        Route::post('/adicionardisciplinas/{id}', '\Modulos\Academico\Http\Controllers\ModulosMatrizesController@postAdicionarDisciplinas')->name('academico.cursos.matrizescurriculares.modulosmatrizes.adicionardisciplinas');
    });

    Route::group(['prefix' => 'disciplinas'], function () {
        Route::get('/', '\Modulos\Academico\Http\Controllers\DisciplinasController@getIndex')->name('academico.disciplinas.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\DisciplinasController@getCreate')->name('academico.disciplinas.create');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\DisciplinasController@postCreate')->name('academico.disciplinas.create');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\DisciplinasController@getEdit')->name('academico.disciplinas.edit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\DisciplinasController@putEdit')->name('academico.disciplinas.edit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\DisciplinasController@postDelete')->name('academico.disciplinas.delete');
    });

    Route::group(['prefix' => 'periodosletivos'], function () {
        Route::get('/', '\Modulos\Academico\Http\Controllers\PeriodosLetivosController@getIndex')->name('academico.periodosletivos.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\PeriodosLetivosController@getCreate')->name('academico.periodosletivos.create');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\PeriodosLetivosController@postCreate')->name('academico.periodosletivos.create');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\PeriodosLetivosController@getEdit')->name('academico.periodosletivos.edit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\PeriodosLetivosController@putEdit')->name('academico.periodosletivos.edit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\PeriodosLetivosController@postDelete')->name('academico.periodosletivos.delete');
    });

    Route::group(['prefix' => 'ofertascursos'], function () {
        Route::get('/', '\Modulos\Academico\Http\Controllers\OfertasCursosController@getIndex')->name('academico.ofertascursos.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\OfertasCursosController@getCreate')->name('academico.ofertascursos.create');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\OfertasCursosController@getEdit')->name('academico.ofertascursos.edit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\OfertasCursosController@putEdit')->name('academico.ofertascursos.edit');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\OfertasCursosController@postCreate')->name('academico.ofertascursos.create');
    });

    Route::group(['prefix' => 'turmas', 'middleware' => ['vinculo_turmas']], function () {
        Route::get('/index/{id}', '\Modulos\Academico\Http\Controllers\TurmasController@getIndex')->name('academico.ofertascursos.turmas.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\TurmasController@getCreate')->name('academico.ofertascursos.turmas.create');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\TurmasController@postCreate')->name('academico.ofertascursos.turmas.create');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\TurmasController@getEdit')->name('academico.ofertascursos.turmas.edit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\TurmasController@putEdit')->name('academico.ofertascursos.turmas.edit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\TurmasController@postDelete')->name('academico.ofertascursos.turmas.delete');
    });

    Route::group(['prefix' => 'grupos', 'middleware' => ['vinculo_turmas']], function () {
        Route::get('/index/{id}', '\Modulos\Academico\Http\Controllers\GruposController@getIndex')->name('academico.ofertascursos.turmas.grupos.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\GruposController@getCreate')->name('academico.ofertascursos.turmas.grupos.create');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\GruposController@postCreate')->name('academico.ofertascursos.turmas.grupos.create');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\GruposController@getEdit')->name('academico.ofertascursos.turmas.grupos.edit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\GruposController@putEdit')->name('academico.ofertascursos.turmas.grupos.edit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\GruposController@postDelete')->name('academico.ofertascursos.turmas.grupos.delete');
        Route::get('/movimentacoes/{id}', '\Modulos\Academico\Http\Controllers\GruposController@getMovimentacoes')->name('academico.ofertascursos.turmas.grupos.movimentacoes');
    });

    Route::group(['prefix' => 'tutoresgrupos', 'middleware' => ['vinculo_turmas']], function () {
        Route::get('/index/{id}', '\Modulos\Academico\Http\Controllers\TutoresGruposController@getIndex')->name('academico.ofertascursos.turmas.grupos.tutoresgrupos.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\TutoresGruposController@getCreate')->name('academico.ofertascursos.turmas.grupos.tutoresgrupos.create');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\TutoresGruposController@postCreate')->name('academico.ofertascursos.turmas.grupos.tutoresgrupos.create');
        Route::get('/alterartutor/{id}', '\Modulos\Academico\Http\Controllers\TutoresGruposController@getAlterarTutor')->name('academico.ofertascursos.turmas.grupos.tutoresgrupos.alterartutor');
        Route::put('/alterartutor/{id}', '\Modulos\Academico\Http\Controllers\TutoresGruposController@putAlterarTutor')->name('academico.ofertascursos.turmas.grupos.tutoresgrupos.alterartutor');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\TutoresGruposController@postDelete')->name('academico.ofertascursos.turmas.grupos.tutoresgrupos.delete');
    });

    Route::group(['prefix' => 'ofertasdisciplinas'], function () {
        Route::get('/', '\Modulos\Academico\Http\Controllers\OfertasDisciplinasController@getIndex')->name('academico.ofertasdisciplinas.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\OfertasDisciplinasController@getCreate')->name('academico.ofertasdisciplinas.create');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\OfertasDisciplinasController@getEdit')->name('academico.ofertasdisciplinas.edit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\OfertasDisciplinasController@putEdit')->name('academico.ofertasdisciplinas.edit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\OfertasDisciplinasController@postDelete')->name('academico.ofertasdisciplinas.delete');
    });

    Route::group(['prefix' => 'matricularalunocurso'], function () {
        Route::get('/', '\Modulos\Academico\Http\Controllers\MatriculaCursoController@getIndex')->name('academico.matricularalunocurso.index');
        Route::get('/create/{id}', '\Modulos\Academico\Http\Controllers\MatriculaCursoController@getCreate')->name('academico.matricularalunocurso.create');
        Route::post('/create/{id}', '\Modulos\Academico\Http\Controllers\MatriculaCursoController@postCreate')->name('academico.matricularalunocurso.create');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\MatriculaCursoController@putEdit')->name('academico.matricularalunocurso.edit');
        Route::get('/show/{id}', '\Modulos\Academico\Http\Controllers\MatriculaCursoController@getShow')->name('academico.matricularalunocurso.show');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\MatriculaCursoController@postDelete')->name('academico.matricularalunocurso.delete');
    });

    Route::group(['prefix' => 'matricularalunodisciplina'], function () {
        Route::get('/', '\Modulos\Academico\Http\Controllers\MatriculasOfertasDisciplinasController@getIndex')->name('academico.matriculasofertasdisciplinas.index');
        Route::get('/show/{id}', '\Modulos\Academico\Http\Controllers\MatriculasOfertasDisciplinasController@getShow')->name('academico.matriculasofertasdisciplinas.show');
    });

    Route::group(['prefix' => 'aproveitamentoestudos'], function () {
        Route::get('/', '\Modulos\Academico\Http\Controllers\AproveitamentoEstudosController@getIndex')->name('academico.aproveitamentoestudos.index');
        Route::get('/show/{id}', '\Modulos\Academico\Http\Controllers\AproveitamentoEstudosController@getShow')->name('academico.aproveitamentoestudos.show');
        Route::post('/aproveitar/{ofertaId}/{matriculaId}', '\Modulos\Academico\Http\Controllers\AproveitamentoEstudosController@postAproveitarDisciplina')->name('academico.aproveitamentoestudos.aproveitardisciplina');
    });

    Route::group(['prefix' => 'matriculaslote'], function () {
        Route::get('/', '\Modulos\Academico\Http\Controllers\MatriculasLoteController@getIndex')->name('academico.matriculaslote.index');
    });

    Route::group(['prefix' => 'lancamentostccs'], function () {
        Route::get('/', '\Modulos\Academico\Http\Controllers\LancamentosTccsController@getIndex')->name('academico.lancamentostccs.index');
        Route::get('/alunosturma/{id}', '\Modulos\Academico\Http\Controllers\LancamentosTccsController@getAlunosTurma')->name('academico.lancamentostccs.alunosturma');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\LancamentosTccsController@getCreate')->name('academico.lancamentostccs.create');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\LancamentosTccsController@postCreate')->name('academico.lancamentostccs.create');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\LancamentosTccsController@getEdit')->name('academico.lancamentostccs.edit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\LancamentosTccsController@putEdit')->name('academico.lancamentostccs.edit');
        Route::get('/anexo/{id}', '\Modulos\Academico\Http\Controllers\LancamentosTccsController@getTccAnexo')->name('academico.lancamentostccs.anexo');
    });

    Route::group(['prefix' => 'conclusaocurso'], function () {
        Route::get('/', '\Modulos\Academico\Http\Controllers\ConclusaoCursoController@getIndex')->name('academico.conclusaocurso.index');
    });

    Route::group(['prefix' => 'historicoparcial'], function () {
        Route::get('/', '\Modulos\Academico\Http\Controllers\HistoricoParcialController@getIndex')->name('academico.historicoparcial.index');
        Route::get('/show/{id}', '\Modulos\Academico\Http\Controllers\HistoricoParcialController@getShow')->name('academico.historicoparcial.show');
        Route::get('/print/{id}', '\Modulos\Academico\Http\Controllers\HistoricoParcialController@getPrint')->name('academico.historicoparcial.print');
    });

    Route::group(['prefix' => 'historicodefinitivo'], function () {
        Route::get('/', '\Modulos\Academico\Http\Controllers\HistoricoDefinitivoController@getIndex')->name('academico.historicodefinitivo.index');
        Route::post('/print', '\Modulos\Academico\Http\Controllers\HistoricoDefinitivoController@postPrint')->name('academico.historicodefinitivo.print');
    });

    Route::group(['prefix' => 'certificacao'], function () {
        Route::get('/', '\Modulos\Academico\Http\Controllers\CertificacaoController@getIndex')->name('academico.certificacao.index');
    });

    Route::group(['prefix' => 'lancamentodenotas'], function () {
        Route::get('/', '\Modulos\Academico\Http\Controllers\LancamentoNotas@getIndex')->name('academico.lancamentonotas.index');
    });

    Route::group(['prefix' => 'carteirasestudantis'], function () {
        Route::get('/', '\Modulos\Academico\Http\Controllers\ListaSemturController@getIndex')->name('academico.carteirasestudantis.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\ListaSemturController@getCreate')->name('academico.carteirasestudantis.create');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\ListaSemturController@postCreate')->name('academico.carteirasestudantis.create');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\ListaSemturController@getEdit')->name('academico.carteirasestudantis.edit');
        Route::post('/edit/{id}', '\Modulos\Academico\Http\Controllers\ListaSemturController@postEdit')->name('academico.carteirasestudantis.edit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\ListaSemturController@postDelete')->name('academico.carteirasestudantis.delete');
        Route::get('/show/{id}', '\Modulos\Academico\Http\Controllers\ListaSemturController@getShowMatriculas')->name('academico.carteirasestudantis.showmatriculas');
        Route::get('/addmatriculas/{id}', '\Modulos\Academico\Http\Controllers\ListaSemturController@getAddMatriculas')->name('academico.carteirasestudantis.addmatriculas');
        Route::get('/exportfile/{lista}/{turma}', '\Modulos\Academico\Http\Controllers\ListaSemturController@exportFile')->name('academico.carteirasestudantis.exportfile');
        Route::get('/print/{lista}/{turma}', '\Modulos\Academico\Http\Controllers\ListaSemturController@getPrint')->name('academico.carteirasestudantis.print');
        Route::post('/deletematricula', '\Modulos\Academico\Http\Controllers\ListaSemturController@postDeleteMatricula')->name('academico.carteirasestudantis.deletematricula');
    });

    Route::group(['prefix' => 'diplomas'], function () {
        Route::get('/', '\Modulos\Academico\Http\Controllers\DiplomasController@getIndex')->name('academico.diplomas.index');
        Route::post('/imprimirdiplomas', '\Modulos\Academico\Http\Controllers\DiplomasController@postPrint')->name('academico.diplomas.imprimirdiplomas');
    });

    Route::group(['prefix' => 'relatoriosmatriculascurso'], function () {
        Route::get('/', '\Modulos\Academico\Http\Controllers\RelatoriosMatriculasCursoController@getIndex')->name('academico.relatoriosmatriculascurso.index');
        Route::post('/pdf', '\Modulos\Academico\Http\Controllers\RelatoriosMatriculasCursoController@postPdf')->name('academico.relatoriosmatriculascurso.pdf');
        Route::post('/xls', '\Modulos\Academico\Http\Controllers\RelatoriosMatriculasCursoController@postXls')->name('academico.relatoriosmatriculascurso.xls');
    });

    Route::group(['prefix' => 'relatoriossisuab'], function () {
        Route::get('/', '\Modulos\Academico\Http\Controllers\RelatoriosSisuabController@getIndex')->name('academico.relatoriossisuab.index');
        Route::post('/pdf', '\Modulos\Academico\Http\Controllers\RelatoriosSisuabController@postCsv')->name('academico.relatoriossisuab.csv');
//        Route::post('/xls', '\Modulos\Academico\Http\Controllers\RelatoriosSisuabController@postXls')->name('academico.relatoriossisuab.xls');
    });

    Route::group(['prefix' => 'relatoriosmatriculasdisciplina'], function () {
        Route::get('/', '\Modulos\Academico\Http\Controllers\RelatoriosMatriculasDisciplinaController@getIndex')->name('academico.relatoriosmatriculasdisciplinas.index');
        Route::post('/pdf', '\Modulos\Academico\Http\Controllers\RelatoriosMatriculasDisciplinaController@postPdf')->name('academico.relatoriosmatriculasdisciplinas.pdf');
        Route::post('/xls', '\Modulos\Academico\Http\Controllers\RelatoriosMatriculasDisciplinaController@postXls')->name('academico.relatoriosmatriculasdisciplinas.xls');
    });

    Route::group(['prefix' => 'relatoriosatasfinais'], function () {
        Route::get('/', '\Modulos\Academico\Http\Controllers\RelatoriosAtasFinaisController@getIndex')->name('academico.relatoriosatasfinais.index');
        Route::post('/pdf', '\Modulos\Academico\Http\Controllers\RelatoriosAtasFinaisController@postPdf')->name('academico.relatoriosatasfinais.pdf');
    });

    Route::group(['prefix' => 'controlederegistro'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\ControleRegistroController@getIndex')->name('academico.controlederegistro.index');
        Route::get('/show/{id}', '\Modulos\Academico\Http\Controllers\ControleRegistroController@getShow')->name('academico.controlederegistro.show');
    });

    //Rotas de funções assíncronas
    Route::group(['prefix' => 'async'], function () {
        Route::group(['prefix' => 'dashboard'], function () {
            Route::get('/cursopornivel', '\Modulos\Academico\Http\Controllers\Async\Index@getCursoPorNivelData')->name('academico.async.dashboard.cursopornivel');
            Route::get('/matriculasstatus', '\Modulos\Academico\Http\Controllers\Async\Index@getMatriculaPorStatusData')->name('academico.async.dashboard.matriculasstatus');
            Route::get('/matriculasmes', '\Modulos\Academico\Http\Controllers\Async\Index@getMatriculasPorMes')->name('academico.async.dashboard.matriculasmes');
        });

        Route::group(['prefix' => 'matrizescurriculares'], function () {
            Route::get('/findallbycurso/{id}', '\Modulos\Academico\Http\Controllers\Async\MatrizesCurriculares@getFindallbycurso')->name('academico.async.matrizescurriculares.findallbycurso');
            Route::get('/findbyofertacurso/{id}', '\Modulos\Academico\Http\Controllers\Async\MatrizesCurriculares@getFindByOfertaCurso')->name('academico.async.matrizescurriculares.findbyofertacurso');
            Route::post('/removeanexo', '\Modulos\Academico\Http\Controllers\Async\MatrizesCurriculares@postRemoveAnexo')->name('academico.async.matrizescurriculares.removeanexo');
        });

        Route::group(['prefix' => 'turmas'], function () {
            Route::get('/findallbyofertacurso/{id}', '\Modulos\Academico\Http\Controllers\Async\Turmas@getFindallbyofertacurso')->name('academico.async.turmas.findallbyofertacurso');
            Route::get('/findallbyofertacursointegrada/{id}', '\Modulos\Academico\Http\Controllers\Async\Turmas@getFindallbyofertacursoIntegrada')->name('academico.async.turmas.findallbyofertacursointegrada');
            Route::get('/findallbyofertacursonaointegrada/{id}', '\Modulos\Academico\Http\Controllers\Async\Turmas@getFindallbyofertacursoNaoIntegrada')->name('academico.async.turmas.findallbyofertacursonaointegrada');
            Route::get('/findallwithvagasdisponiveis/{id}', '\Modulos\Academico\Http\Controllers\Async\Turmas@getFindallwithvagasdisponiveis')->name('academico.async.turmas.findallwithvagasdisponiveis');
            Route::get('/findallbyofertacursowithoutambiente/{id}', '\Modulos\Academico\Http\Controllers\Async\Turmas@getFindallbyofertacursoWithoutAmbiente')->name('academico.async.turmas.findallbyofertacursowithoutambiente');
            Route::get('/buscaturmasseminstituicao/{id}', '\Modulos\Academico\Http\Controllers\Async\Turmas@buscaTurmasSemInstituicao')->name('academico.async.turmas.buscaturmasseminstituicao');
        });

        Route::group(['prefix' => 'polos'], function () {
            Route::get('/findallbyofertacurso/{id}', '\Modulos\Academico\Http\Controllers\Async\Polos@getFindallbyofertacurso')->name('academico.async.polos.findallbyofertacurso');
        });

        Route::group(['prefix' => 'ofertascursos'], function () {
            Route::get('/findallbycurso/{id}', '\Modulos\Academico\Http\Controllers\Async\OfertasCursos@getFindallbycurso')->name('academico.async.ofertascursos.findallbycurso');
            Route::get('/findallbycursowithoutpresencial/{id}', '\Modulos\Academico\Http\Controllers\Async\OfertasCursos@getFindallbycursowithoutpresencial')->name('academico.async.ofertascursos.findallbycursowithoutpresencial');
            Route::get('/findallbycursowithoutead/{id}', '\Modulos\Academico\Http\Controllers\Async\OfertasCursos@getFindAllByCursoWithoutEad')->name('academico.async.ofertascursos.findallbycursowithoutead');
        });

        Route::group(['prefix' => 'disciplinas'], function () {
            Route::get('/findbynome/{matriz}/{nome}/{modulo}', '\Modulos\Academico\Http\Controllers\Async\Disciplinas@getFindByNome')->name('academico.async.disciplinas.findbynome');
        });

        Route::group(['prefix' => 'modulosdisciplinas'], function () {
            Route::get('/getalldisciplinasbymodulo/{modulo}', '\Modulos\Academico\Http\Controllers\Async\ModulosDisciplinas@getAllDisciplinasByModulo')->name('academico.async.modulosdisciplinas.getalldisciplinasbymodulo');
            Route::get('/getdisciplinasnotofertadasbymodulo/{modulo}/{turma}/{periodo}', '\Modulos\Academico\Http\Controllers\Async\ModulosDisciplinas@getDisciplinasNotOfertadasByModulo')->name('academico.async.modulosdisciplinas.getdisciplinasnotofertadasbymodulo');
            Route::get('/getdisciplina/{id}', '\Modulos\Academico\Http\Controllers\Async\ModulosDisciplinas@getDisciplina')->name('academico.async.modulosdisciplinas.getdisciplina');
            Route::post('/adicionardisciplina/', '\Modulos\Academico\Http\Controllers\Async\ModulosDisciplinas@postAdicionarDisciplina')->name('academico.async.modulosdisciplinas.adicionardisciplina');
            Route::put('/editardisciplina/', '\Modulos\Academico\Http\Controllers\Async\ModulosDisciplinas@putEditDisciplina')->name('academico.async.modulosdisciplinas.editardisciplina');
            Route::post('/deletardisciplina/', '\Modulos\Academico\Http\Controllers\Async\ModulosDisciplinas@postDeletarDisciplina')->name('academico.async.modulosdisciplinas.deletardisciplina');
        });

        Route::group(['prefix' => 'modulosmatriz'], function () {
            Route::get('/findallbymatriz/{id}', '\Modulos\Academico\Http\Controllers\Async\ModuloMatriz@getFindallbymatriz')->name('academico.async.modulosmatriz.findallbymatriz');
        });

        Route::group(['prefix' => 'ofertasturma'], function () {
            Route::get('/ofertabyturma/{id}', '\Modulos\Academico\Http\Controllers\Async\OfertasCursos@getOfertabyturma')->name('academico.async.ofertasturma.ofertabyturma');
        });

        Route::group(['prefix' => 'ofertasdisciplinas'], function () {
            Route::get('/findall', '\Modulos\Academico\Http\Controllers\Async\OfertaDisciplina@getFindall')->name('academico.async.ofertasdisciplinas.findall');
            Route::post('/oferecerdisciplina', '\Modulos\Academico\Http\Controllers\Async\OfertaDisciplina@postOferecerdisciplina')->name('academico.async.ofertasdisciplinas.oferecerdisciplina');
            Route::get('/gettableofertasdisciplinas', '\Modulos\Academico\Http\Controllers\Async\OfertaDisciplina@getTableOfertasDisciplinas')->name('academico.async.ofertasdisciplinas.gettableofertasdisciplinas');
            Route::get('/gettabledisciplinasnaoofertadas', '\Modulos\Academico\Http\Controllers\Async\OfertaDisciplina@getTableDisciplinasNaoOfertadas')->name('academico.async.ofertasdisciplinas.gettabledisciplinasnaoofertadas');
        });

        Route::group(['prefix' => 'matriculasofertasdisciplinas'], function () {
            Route::get('/gettableofertasdisciplinas/{one}/{two}/{three}', '\Modulos\Academico\Http\Controllers\Async\MatriculaOfertaDisciplina@getTableOfertasDisciplinas')->name('academico.async.matriculasofertasdisciplinas.gettableofertasdisciplinas');
            Route::post('/matricular', '\Modulos\Academico\Http\Controllers\Async\MatriculaOfertaDisciplina@postMatricularAlunoDisciplinas')->name('academico.async.matriculasofertasdisciplinas.matricular');
            Route::post('/desmatricular', '\Modulos\Academico\Http\Controllers\Async\MatriculaOfertaDisciplina@postDesmatricularAlunoDisciplinas')->name('academico.async.matriculasofertasdisciplinas.desmatricular');
            Route::get('/getalunosmatriculaslote', '\Modulos\Academico\Http\Controllers\Async\MatriculaOfertaDisciplina@getFindAllAlunosMatriculasLote')->name('academico.async.matriculasofertasdisciplinas.getalunosmatriculaslote');
            Route::post('/matriculaslote', '\Modulos\Academico\Http\Controllers\Async\MatriculaOfertaDisciplina@postMatriculasLote')->name('academico.async.matriculasofertasdisciplinas.matriculaslote');
            Route::post('/desmatricularlote', '\Modulos\Academico\Http\Controllers\Async\MatriculaOfertaDisciplina@postDesmatricularLote')->name('academico.async.matriculasofertasdisciplinas.desmatricularlote');
            Route::get('/gettallalunosbysituacao/{one}/{two}/{three}', '\Modulos\Academico\Http\Controllers\Async\MatriculaOfertaDisciplina@getRelatorio')->name('academico.async.matriculasofertasdisciplinas.gettallalunosbysituacao');
        });

        Route::group(['prefix' => 'aproveitamentoestudos'], function () {
            Route::get('/gettableofertasdisciplinas/{aluno}/{turma}/{periodo?}', '\Modulos\Academico\Http\Controllers\Async\AproveitamentoEstudos@getTableOfertasDisciplinas')->name('academico.async.aproveitamentoestudos.gettableofertasdisciplinas');
            Route::get('/getmodal/{oferta}/{matricula}', '\Modulos\Academico\Http\Controllers\Async\AproveitamentoEstudos@getModal')->name('academico.async.aproveitamentoestudos.getmodal');
        });

        Route::group(['prefix' => 'grupos'], function () {
            Route::get('/findallbyturma/{id}', '\Modulos\Academico\Http\Controllers\Async\Grupos@getFindallbyturma')->name('academico.async.grupos.findallbyturma');
            Route::get('/findallbyturmapolo/{one}/{two}', '\Modulos\Academico\Http\Controllers\Async\Grupos@getFindallbyturmapolo')->name('academico.async.grupos.findallbyturmapolo');
        });

        Route::group(['prefix' => 'tutores'], function () {
            Route::get('/findallbygrupo/{id}', '\Modulos\Academico\Http\Controllers\Async\Tutores@getFindallbygrupo')->name('academico.async.tutores.findallbygrupo');
            Route::get('/findallbyturmatipotutoria/{idTurma}/{tipoTutoria}', '\Modulos\Academico\Http\Controllers\Async\Tutores@getFindallbyTurmaTipoTutoria')->name('academico.async.tutores.findallbyturmatipotutoria');
        });

        Route::group(['prefix' => 'periodosletivos'], function () {
            Route::get('/findallbyturma/{id}', '\Modulos\Academico\Http\Controllers\Async\PeriodoLetivo@getFindallbyturma')->name('academico.async.periodosletivos.findallbyturma');
        });

        Route::group(['prefix' => 'professores'], function () {
            Route::get('/findall', '\Modulos\Academico\Http\Controllers\Async\Professor@getFindall')->name('academico.async.professores.findall');
        });

        Route::group(['prefix' => 'conclusaocurso'], function () {
            Route::get('/findallalunosaptosounao', '\Modulos\Academico\Http\Controllers\Async\ConclusaoCurso@getAllalunosaptosounao')->name('academico.async.conclusaocurso.findallalunosaptosounao');
            Route::post('/concluirmatriculas', '\Modulos\Academico\Http\Controllers\Async\ConclusaoCurso@postConcluirMatriculas')->name('academico.async.conclusaocurso.concluirmatriculas');
        });

        Route::group(['prefix' => 'anexos'], function () {
            Route::post('/deletaranexolancamentotcc', '\Modulos\Academico\Http\Controllers\Async\LancamentosTccs@postDeletarAnexo')->name('academico.async.anexos.deletaranexolancamentotcc');
        });

        Route::group(['prefix' => 'cursos'], function () {
            Route::get('/findcursostecnicos', '\Modulos\Academico\Http\Controllers\Async\Cursos@getCursosTecnicos')->name('academico.async.cursos.findcursostecnicos');
            Route::get('/findmodulosbyoferta/{id}', '\Modulos\Academico\Http\Controllers\Async\Cursos@getModulosByOferta')->name('academico.async.cursos.findmodulosbyoferta');
            Route::get('/getalunosaptos/{turma}/{modulo}/{polo?}', '\Modulos\Academico\Http\Controllers\Async\Cursos@getAlunosAptos')->name('academico.async.cursos.getalunosaptos');
            Route::post('/certificaralunos', '\Modulos\Academico\Http\Controllers\Async\Cursos@postCertificarAlunos')->name('academico.async.cursos.certificaralunos');
            Route::get('/printCertificado/{idMatricula}/{idModulo}', '\Modulos\Academico\Http\Controllers\CertificacaoController@getPrint')->name('academico.async.cursos.printcertificado');
        });

        Route::group(['prefix' => 'diplomas'], function () {
            Route::get('/getalunosdiplomados/{turmaId}/{poloId}', '\Modulos\Academico\Http\Controllers\Async\Diplomas@getAlunosDiplomados')->name('academico.async.diplomas.getalunosdiplomados');
            Route::post('/diplomaralunos', '\Modulos\Academico\Http\Controllers\Async\Diplomas@postDiplomarAlunos')->name('academico.async.diplomas.diplomaralunos');
        });

        Route::group(['prefix' => 'matricula'], function () {
            Route::post('/alterarsituacao', '\Modulos\Academico\Http\Controllers\Async\Matricula@postUpdateSituacao')->name('academico.async.matricula.alterarsituacao');
            Route::get('/getmatriculasconcluidas', '\Modulos\Academico\Http\Controllers\Async\Matricula@getMatriculasConcluidas')->name('academico.async.matricula.getmatriculasconcluidas');
        });

        Route::group(['prefix' => 'lancamentodenotas'], function () {
            Route::get('/', '\Modulos\Academico\Http\Controllers\Async\LancamentoNotas@getTable')->name('academico.async.lancamentonotas.table');
            Route::post('/', '\Modulos\Academico\Http\Controllers\Async\LancamentoNotas@postNotas')->name('academico.async.lancamentonotas.create');
        });

        Route::group(['prefix' => 'carteirasestudantis'], function () {
            Route::get('/gettableshowmatriculas/{lista}/{turma}', '\Modulos\Academico\Http\Controllers\Async\ListaSemtur@getTableShowMatriculas')->name('academico.async.carteirasestudantis.gettableshowmatriculas');
            Route::get('/gettableaddmatriculas', '\Modulos\Academico\Http\Controllers\Async\ListaSemtur@getTableAddMatriculas')->name('academico.async.carteirasestudantis.gettableaddmatriculas');
            Route::post('/incluirmatriculas', '\Modulos\Academico\Http\Controllers\Async\ListaSemtur@postIncluirMatriculasLista')->name('academico.async.carteirasestudantis.incluirmatriculas');
        });
    });
});
