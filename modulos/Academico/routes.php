<?php

Route::group(['prefix' => 'academico', 'middleware' => ['auth']], function () {
    Route::get('/', '\Modulos\Academico\Http\Controllers\indexController@getIndex')->name('academico.index.index');

    Route::group(['prefix' => 'polos'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\PolosController@getIndex')->name('academico.polos.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\PolosController@getCreate')->name('academico.polos.create');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\PolosController@postCreate')->name('academico.polos.create');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\PolosController@getEdit')->name('academico.polos.edit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\PolosController@putEdit')->name('academico.polos.edit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\PolosController@postDelete')->name('academico.polos.delete');
    });

    Route::group(['prefix' => 'departamentos'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\DepartamentosController@getIndex')->name('academico.departamentos.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\DepartamentosController@getCreate')->name('academico.departamentos.create');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\DepartamentosController@postCreate')->name('academico.departamentos.create');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\DepartamentosController@getEdit')->name('academico.departamentos.edit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\DepartamentosController@putEdit')->name('academico.departamentos.edit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\DepartamentosController@postDelete')->name('academico.departamentos.delete');
    });

    Route::group(['prefix' => 'periodosletivos'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\PeriodosLetivosController@getIndex')->name('academico.periodosletivos.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\PeriodosLetivosController@getCreate')->name('academico.periodosletivos.create');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\PeriodosLetivosController@postCreate')->name('academico.periodosletivos.create');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\PeriodosLetivosController@getEdit')->name('academico.periodosletivos.edit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\PeriodosLetivosController@putEdit')->name('academico.periodosletivos.edit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\PeriodosLetivosController@postDelete')->name('academico.periodosletivos.delete');
    });

    Route::group(['prefix' => 'cursos'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\CursosController@getIndex')->name('academico.cursos.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\CursosController@getCreate')->name('academico.cursos.create');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\CursosController@postCreate')->name('academico.cursos.create');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\CursosController@getEdit')->name('academico.cursos.edit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\CursosController@putEdit')->name('academico.cursos.edit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\CursosController@postDelete')->name('academico.cursos.delete');
    });

    Route::group(['prefix' => 'matrizescurriculares'], function () {
        Route::get('/index/{id}', '\Modulos\Academico\Http\Controllers\MatrizesCurricularesController@getIndex')->name('academico.cursos.matrizescurriculares.index');
        Route::get('/create/{id}', '\Modulos\Academico\Http\Controllers\MatrizesCurricularesController@getCreate')->name('academico.cursos.matrizescurriculares.create');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\MatrizesCurricularesController@postCreate')->name('academico.cursos.matrizescurriculares.create');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\MatrizesCurricularesController@getEdit')->name('academico.cursos.matrizescurriculares.edit');
        Route::get('/anexo/{id}', '\Modulos\Academico\Http\Controllers\MatrizesCurricularesController@getMatrizAnexo')->name('academico.cursos.matrizescurriculares.anexo');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\MatrizesCurricularesController@putEdit')->name('academico.cursos.matrizescurriculares.edit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\MatrizesCurricularesController@postDelete')->name('academico.cursos.matrizescurriculares.delete');
    });

    Route::group(['prefix' => 'centros'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\CentrosController@getIndex')->name('academico.centros.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\CentrosController@getCreate')->name('academico.centros.create');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\CentrosController@postCreate')->name('academico.centros.create');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\CentrosController@getEdit')->name('academico.centros.edit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\CentrosController@putEdit')->name('academico.centros.edit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\CentrosController@postDelete')->name('academico.centros.delete');
    });

    Route::group(['prefix' => 'ofertascursos'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\OfertasCursosController@getIndex')->name('academico.ofertascursos.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\OfertasCursosController@getCreate')->name('academico.ofertascursos.create');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\OfertasCursosController@postCreate')->name('academico.ofertascursos.create');
    });

    Route::group(['prefix' => 'grupos', 'middleware' => ['vinculo']], function () {
        Route::get('/index/{id}', '\Modulos\Academico\Http\Controllers\GruposController@getIndex')->name('academico.ofertascursos.turmas.grupos.index');
        Route::get('/create/{id}', '\Modulos\Academico\Http\Controllers\GruposController@getCreate')->name('academico.ofertascursos.turmas.grupos.create');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\GruposController@postCreate')->name('academico.ofertascursos.turmas.grupos.create');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\GruposController@getEdit')->name('academico.ofertascursos.turmas.grupos.edit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\GruposController@putEdit')->name('academico.ofertascursos.turmas.grupos.edit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\GruposController@postDelete')->name('academico.ofertascursos.turmas.grupos.delete');
    });

    Route::group(['prefix' => 'turmas', 'middleware' => ['vinculo']], function () {
        Route::get('/index/{id}', '\Modulos\Academico\Http\Controllers\TurmasController@getIndex')->name('academico.ofertascursos.turmas.index');
        Route::get('/create/{id}', '\Modulos\Academico\Http\Controllers\TurmasController@getCreate')->name('academico.ofertascursos.turmas.create');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\TurmasController@postCreate')->name('academico.ofertascursos.turmas.create');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\TurmasController@getEdit')->name('academico.ofertascursos.turmas.edit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\TurmasController@putEdit')->name('academico.ofertascursos.turmas.edit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\TurmasController@postDelete')->name('academico.ofertascursos.turmas.delete');
    });

    Route::group(['prefix' => 'tutoresgrupos', 'middleware' => ['vinculo']], function () {
        Route::get('/index/{id}', '\Modulos\Academico\Http\Controllers\TutoresGruposController@getIndex')->name('academico.ofertascursos.turmas.grupos.tutoresgrupos.index');
        Route::get('/create/{id}', '\Modulos\Academico\Http\Controllers\TutoresGruposController@getCreate')->name('academico.ofertascursos.turmas.grupos.tutoresgrupos.create');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\TutoresGruposController@postCreate')->name('academico.ofertascursos.turmas.grupos.tutoresgrupos.create');
        Route::get('/alterartutor/{id}', '\Modulos\Academico\Http\Controllers\TutoresGruposController@getAlterarTutor')->name('academico.ofertascursos.turmas.grupos.tutoresgrupos.alterartutor');
        Route::put('/alterartutor/{id}', '\Modulos\Academico\Http\Controllers\TutoresGruposController@putAlterarTutor')->name('academico.ofertascursos.turmas.grupos.tutoresgrupos.alterartutor');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\TutoresGruposController@postDelete')->name('academico.ofertascursos.turmas.grupos.tutoresgrupos.delete');
    });

    Route::group(['prefix' => 'disciplinas'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\DisciplinasController@getIndex')->name('academico.disciplinas.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\DisciplinasController@getCreate')->name('academico.disciplinas.create');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\DisciplinasController@postCreate')->name('academico.disciplinas.create');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\DisciplinasController@getEdit')->name('academico.disciplinas.edit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\DisciplinasController@putEdit')->name('academico.disciplinas.edit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\DisciplinasController@postDelete')->name('academico.disciplinas.delete');
    });

    Route::group(['prefix' => 'modulosmatrizes'], function () {
        Route::get('/index/{id}', '\Modulos\Academico\Http\Controllers\ModulosMatrizesController@getIndex')->name('academico.cursos.matrizescurriculares.modulosmatrizes.index');
        Route::get('/create/{id}', '\Modulos\Academico\Http\Controllers\ModulosMatrizesController@getCreate')->name('academico.cursos.matrizescurriculares.modulosmatrizes.create');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\ModulosMatrizesController@postCreate')->name('academico.cursos.matrizescurriculares.modulosmatrizes.create');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\ModulosMatrizesController@getEdit')->name('academico.cursos.matrizescurriculares.modulosmatrizes.edit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\ModulosMatrizesController@putEdit')->name('academico.cursos.matrizescurriculares.modulosmatrizes.edit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\ModulosMatrizesController@postDelete')->name('academico.cursos.matrizescurriculares.modulosmatrizes.delete');
        Route::get('/gerenciardisciplinas/{id}', '\Modulos\Academico\Http\Controllers\ModulosMatrizesController@getGerenciarDisciplinas')->name('academico.cursos.matrizescurriculares.modulosmatrizes.gerenciardisciplinas');
        Route::post('/adicionardisciplinas/{id}', '\Modulos\Academico\Http\Controllers\ModulosMatrizesController@postAdicionarDisciplinas')->name('academico.cursos.matrizescurriculares.modulosmatrizes.adicionardisciplinas');
    });

    Route::group(['prefix' => 'usuarioscursos'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\VinculosController@getIndex')->name('academico.vinculos.index');
        Route::get('/vinculos/{id}', '\Modulos\Academico\Http\Controllers\VinculosController@getVinculos')->name('academico.vinculos.vinculos');
        Route::get('/create/{id}', '\Modulos\Academico\Http\Controllers\VinculosController@getCreate')->name('academico.vinculos.create');
        Route::post('/create/{id}', '\Modulos\Academico\Http\Controllers\VinculosController@postCreate')->name('academico.vinculos.create');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\VinculosController@postDelete')->name('academico.vinculos.delete');
    });

    Route::group(['prefix' => 'tutores'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\TutoresController@getIndex')->name('academico.tutores.index');
        Route::get('/create/{id?}', '\Modulos\Academico\Http\Controllers\TutoresController@getCreate')->name('academico.tutores.create')->middleware('verificapessoa');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\TutoresController@postCreate')->name('academico.tutores.create');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\TutoresController@getEdit')->name('academico.tutores.edit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\TutoresController@putEdit')->name('academico.tutores.edit');
        Route::get('/show/{id}', '\Modulos\Academico\Http\Controllers\TutoresController@getShow')->name('academico.tutores.show');
    });

    Route::group(['prefix' => 'alunos', 'middleware' => ['vinculo']], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\AlunosController@getIndex')->name('academico.alunos.index');
        Route::get('/create/{id?}', '\Modulos\Academico\Http\Controllers\AlunosController@getCreate')->name('academico.alunos.create')->middleware('verificapessoa');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\AlunosController@postCreate')->name('academico.alunos.create');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\AlunosController@getEdit')->name('academico.alunos.edit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\AlunosController@putEdit')->name('academico.alunos.edit');
        Route::get('/show/{id}', '\Modulos\Academico\Http\Controllers\AlunosController@getShow')->name('academico.alunos.show');
    });

    Route::group(['prefix' => 'professores'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\ProfessoresController@getIndex')->name('academico.professores.index');
        Route::get('/create/{id?}', '\Modulos\Academico\Http\Controllers\ProfessoresController@getCreate')->name('academico.professores.create')->middleware('verificapessoa');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\ProfessoresController@postCreate')->name('academico.professores.create');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\ProfessoresController@getEdit')->name('academico.professores.edit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\ProfessoresController@putEdit')->name('academico.professores.edit');
        Route::get('/show/{id}', '\Modulos\Academico\Http\Controllers\ProfessoresController@getShow')->name('academico.professores.show');
    });

    Route::group(['prefix' => 'ofertasdisciplinas'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\OfertasDisciplinasController@getIndex')->name('academico.ofertasdisciplinas.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\OfertasDisciplinasController@getCreate')->name('academico.ofertasdisciplinas.create');
    });

    Route::group(['prefix' => 'matricularalunocurso'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\MatriculaCursoController@getIndex')->name('academico.matricularalunocurso.index');
        Route::get('/create/{id}', '\Modulos\Academico\Http\Controllers\MatriculaCursoController@getCreate')->name('academico.matricularalunocurso.create');
        Route::post('/create/{id}', '\Modulos\Academico\Http\Controllers\MatriculaCursoController@postCreate')->name('academico.matricularalunocurso.create');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\MatriculaCursoController@putEdit')->name('academico.matricularalunocurso.edit');
        Route::get('/show/{id}', '\Modulos\Academico\Http\Controllers\MatriculaCursoController@getShow')->name('academico.matricularalunocurso.show');
    });

    Route::group(['prefix' => 'relatoriosmatriculascurso'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\RelatoriosMatriculasCursoController@getIndex')->name('academico.relatoriosmatriculascurso.index');
        Route::post('/pdf', '\Modulos\Academico\Http\Controllers\RelatoriosMatriculasCursoController@postPdf')->name('academico.relatoriosmatriculascurso.pdf');
    });

    Route::group(['prefix' => 'matricularalunodisciplina', 'middleware' => ['vinculo']], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\MatriculasOfertasDisciplinasController@getIndex')->name('academico.matriculasofertasdisciplinas.index');
        Route::get('/show/{id}', '\Modulos\Academico\Http\Controllers\MatriculasOfertasDisciplinasController@getShow')->name('academico.matriculasofertasdisciplinas.show');
    });

    Route::group(['prefix' => 'relatoriosmatriculasdisciplina'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\RelatoriosMatriculasDisciplinaController@getIndex')->name('academico.relatoriosmatriculasdisciplinas.index');
        Route::post('/pdf', '\Modulos\Academico\Http\Controllers\RelatoriosMatriculasDisciplinaController@postPdf')->name('academico.relatoriosmatriculasdisciplinas.pdf');
    });

    Route::group(['prefix' => 'matriculaslote'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\MatriculasLoteController@getIndex')->name('academico.matriculaslote.index');
    });

    Route::group(['prefix' => 'lancamentostccs'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\LancamentosTccsController@getIndex')->name('academico.lancamentostccs.index');
        Route::get('/alunosturma/{id}', '\Modulos\Academico\Http\Controllers\LancamentosTccsController@getAlunosTurma')->name('academico.lancamentostccs.alunosturma');
        Route::get('/create/{idAluno}/{idTurma}', '\Modulos\Academico\Http\Controllers\LancamentosTccsController@getCreate')->name('academico.lancamentostccs.create');
        Route::post('/create/{idTurma}', '\Modulos\Academico\Http\Controllers\LancamentosTccsController@postCreate')->name('academico.lancamentostccs.create');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\LancamentosTccsController@getEdit')->name('academico.lancamentostccs.edit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\LancamentosTccsController@putEdit')->name('academico.lancamentostccs.edit');
        Route::get('/anexo/{id}', '\Modulos\Academico\Http\Controllers\LancamentosTccsController@getTccAnexo')->name('academico.lancamentostccs.tccanexo');
    });

    Route::group(['prefix' => 'conclusaocurso'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\ConclusaoCursoController@getIndex')->name('academico.conclusaocurso.index');
    });

    Route::group(['prefix' => 'certificacao'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\CertificacaoController@getIndex')->name('academico.certificacao.index');
    });

    Route::group(['prefix' => 'controlederegistro'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\ControleRegistroController@getIndex')->name('academico.certificacao.index');
    });

    Route::group(['prefix' => 'historicoparcial'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\HistoricoParcialController@getIndex')->name('academico.historicoparcial.index');
        Route::get('/show/{id}', '\Modulos\Academico\Http\Controllers\HistoricoParcialController@getShow')->name('academico.historicoparcial.show');
        Route::get('/print/{id}', '\Modulos\Academico\Http\Controllers\HistoricoParcialController@getPrint')->name('academico.historicoparcial.print');
    });

    Route::group(['prefix' => 'historicodefinitivo'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\HistoricoDefinitivoController@getIndex')->name('academico.historicodefinitivo.index');
        Route::post('/print', '\Modulos\Academico\Http\Controllers\HistoricoDefinitivoController@postPrint')->name('academico.historicodefinitivo.print');
    });

    //Rotas de funções assíncronas
    Route::group(['prefix' => 'async'], function () {
        Route::group(['prefix' => 'matrizescurriculares'], function () {
            Route::get('/findallbycurso/{id}', '\Modulos\Academico\Http\Controllers\Async\MatrizesCurriculares@getFindallbycurso')->name('academico.async.matrizescurriculares.findallbycurso');
        });

        Route::group(['prefix' => 'turmas'], function () {
            Route::get('/findallbyofertacurso/{id}', '\Modulos\Academico\Http\Controllers\Async\Turmas@getFindallbyofertacurso')->name('academico.async.turmas.findallbyofertacurso');
            Route::get('/findallwithvagasdisponiveis/{id}', '\Modulos\Academico\Http\Controllers\Async\Turmas@getFindallwithvagasdisponiveis')->name('academico.async.turmas.findallwithvagasdisponiveis');
            Route::get('/findallbyofertacursowithoutambiente/{id}', '\Modulos\Academico\Http\Controllers\Async\Turmas@getFindallbyofertacursoWithoutAmbiente')->name('academico.async.turmas.findallbyofertacursowithoutambiente');
        });

        Route::group(['prefix' => 'polos'], function () {
            Route::get('/findallbyofertacurso/{id}', '\Modulos\Academico\Http\Controllers\Async\Polos@getFindallbyofertacurso')->name('academico.async.polos.findallbyofertacurso');
        });

        Route::group(['prefix' => 'ofertascursos'], function () {
            Route::get('/findallbycurso/{id}', '\Modulos\Academico\Http\Controllers\Async\OfertasCursos@getFindallbycurso')->name('academico.async.ofertascursos.findallbycurso');
            Route::get('/findallbycursowithoutpresencial/{id}', '\Modulos\Academico\Http\Controllers\Async\OfertasCursos@getFindallbycursowithoutpresencial')->name('academico.async.ofertascursos.findallbycursowithoutpresencial');
        });

        Route::group(['prefix' => 'disciplinas'], function () {
            Route::get('/findbynome/{matriz}/{nome}/{modulo}', '\Modulos\Academico\Http\Controllers\Async\Disciplinas@getFindByNome')->name('academico.async.disciplinas.findbynome');
        });

        Route::group(['prefix' => 'modulosdisciplinas'], function () {
            Route::get('/getalldisciplinasbymodulo/{modulo}', '\Modulos\Academico\Http\Controllers\Async\ModulosDisciplinas@getAllDisciplinasByModulo')->name('academico.async.modulosdisciplinas.getalldisciplinasbymodulo');
            Route::get('/getdisciplinasnotofertadasbymodulo/{modulo}/{turma}/{periodo}', '\Modulos\Academico\Http\Controllers\Async\ModulosDisciplinas@getDisciplinasNotOfertadasByModulo')->name('academico.async.modulosdisciplinas.getdisciplinasnotofertadasbymodulo');
            Route::get('/verifydisciplina/{modulo}', '\Modulos\Academico\Http\Controllers\Async\ModulosDisciplinas@verifyDisciplinas')->name('academico.async.modulosdisciplinas.verifydisciplina');
            Route::post('/adicionardisciplina/', '\Modulos\Academico\Http\Controllers\Async\ModulosDisciplinas@postAdicionarDisciplina')->name('academico.async.modulosdisciplinas.adicionardisciplina');
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
            Route::post('/deletarofertadisciplina', '\Modulos\Academico\Http\Controllers\Async\OfertaDisciplina@postDeletarofertadisciplina')->name('academico.async.ofertasdisciplinas.deletarofertadisciplina');
        });

        Route::group(['prefix' => 'matriculasofertasdisciplinas', 'middleware' => ['vinculo']], function () {
            Route::get('/findalldisciplinascursadasbyalunoturmaperiodo/{one}/{two}/{three}', '\Modulos\Academico\Http\Controllers\Async\MatriculaOfertaDisciplina@getFindAllDisciplinasCursadasByAlunoTurmaPeriodo')->name('academico.async.matriculasofertasdisciplinas.findalldisciplinascursadasbyalunoturmaperiodo');
            Route::get('/findalldisciplinasnotcursadasbyalunoturmaperiodo/{one}/{two}/{three}', '\Modulos\Academico\Http\Controllers\Async\MatriculaOfertaDisciplina@getFindAllDisciplinasNotCursadasByAlunoTurmaPeriodo')->name('academico.async.matriculasofertasdisciplinas.findalldisciplinasnotcursadasbyalunoturmaperiodo');
            Route::post('/matricular', '\Modulos\Academico\Http\Controllers\Async\MatriculaOfertaDisciplina@postMatricularAlunoDisciplinas')->name('academico.async.matriculasofertasdisciplinas.matricular');
            Route::get('/getalunosmatriculaslote/{one}/{two}', '\Modulos\Academico\Http\Controllers\Async\MatriculaOfertaDisciplina@getFindAllAlunosMatriculasLote')->name('academico.async.matriculasofertasdisciplinas.getalunosmatriculaslote');
            Route::post('/matriculaslote', '\Modulos\Academico\Http\Controllers\Async\MatriculaOfertaDisciplina@postMatriculasLote')->name('academico.async.matriculasofertasdisciplinas.matriculaslote');
            Route::get('/gettallalunosbysituacao/{one}/{two}/{three}', '\Modulos\Academico\Http\Controllers\Async\MatriculaOfertaDisciplina@getRelatorio')->name('academico.async.matriculasofertasdisciplinas.gettallalunosbysituacao');
        });

        Route::group(['prefix' => 'grupos'], function () {
            Route::get('/findallbyturma/{id}', '\Modulos\Academico\Http\Controllers\Async\Grupos@getFindallbyturma')->name('academico.async.grupos.findallbyturma');
            Route::get('/findallbyturmapolo/{one}/{two}', '\Modulos\Academico\Http\Controllers\Async\Grupos@getFindallbyturmapolo')->name('academico.async.grupos.findallbyturmapolo');
        });

        Route::group(['prefix' => 'tutores'], function () {
            Route::get('/findallbygrupo/{id}', '\Modulos\Academico\Http\Controllers\Async\Tutores@getFindallbygrupo')->name('academico.async.tutores.findallbygrupo');
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
            Route::get('/getalunosaptos/{turma}/{modulo}', '\Modulos\Academico\Http\Controllers\Async\Cursos@getAlunosAptos')->name('academico.async.cursos.getalunosaptos');
            Route::post('/certificaralunos', '\Modulos\Academico\Http\Controllers\Async\Cursos@postCertificarAlunos')->name('academico.async.cursos.certificaralunos');
            Route::get('/printCertificado/{idMatricula}/{idModulo}', '\Modulos\Academico\Http\Controllers\CertificacaoController@getPrint')->name('academico.async.cursos.printcertificado');
        });

        Route::group(['prefix' => 'matricula'], function () {
            Route::post('/alterarsituacao', '\Modulos\Academico\Http\Controllers\Async\Matricula@postUpdateSituacao')->name('academico.async.matricula.alterarsituacao');
            Route::get('/getmatriculasconcluidas', '\Modulos\Academico\Http\Controllers\Async\Matricula@getMatriculasConcluidas')->name('academico.async.matricula.getmatriculasconcluidas');
        });
    });
});
