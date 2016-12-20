<?php

Route::group(['prefix' => 'academico', 'middleware' => ['auth']], function () {
    Route::group(['prefix' => 'index'], function () {
        Route::get('/', '\Modulos\Academico\Http\Controllers\indexController@getIndex')->name('academico.index.index');
        Route::get('/index', '\Modulos\Academico\Http\Controllers\indexController@getIndex')->name('academico.index.getIndex');
    });

    Route::group(['prefix' => 'polos'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\PolosController@getIndex')->name('academico.polos.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\PolosController@getCreate')->name('academico.polos.getCreate');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\PolosController@postCreate')->name('academico.polos.postCreate');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\PolosController@getEdit')->name('academico.polos.getEdit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\PolosController@putEdit')->name('academico.polos.putEdit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\PolosController@postDelete')->name('academico.polos.delete');
    });

    Route::group(['prefix' => 'departamentos'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\DepartamentosController@getIndex')->name('academico.departamentos.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\DepartamentosController@getCreate')->name('academico.departamentos.getCreate');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\DepartamentosController@postCreate')->name('academico.departamentos.postCreate');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\DepartamentosController@getEdit')->name('academico.departamentos.getEdit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\DepartamentosController@putEdit')->name('academico.departamentos.putEdit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\DepartamentosController@postDelete')->name('academico.departamentos.delete');
    });

    Route::group(['prefix' => 'periodosletivos'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\PeriodosLetivosController@getIndex')->name('academico.periodosletivos.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\PeriodosLetivosController@getCreate')->name('academico.periodosletivos.getCreate');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\PeriodosLetivosController@postCreate')->name('academico.periodosletivos.postCreate');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\PeriodosLetivosController@getEdit')->name('academico.periodosletivos.getEdit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\PeriodosLetivosController@putEdit')->name('academico.periodosletivos.putEdit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\PeriodosLetivosController@postDelete')->name('academico.periodosletivos.delete');
    });

    Route::group(['prefix' => 'cursos', 'middleware' => ['vinculo']], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\CursosController@getIndex')->name('academico.cursos.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\CursosController@getCreate')->name('academico.cursos.getCreate');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\CursosController@postCreate')->name('academico.cursos.postCreate');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\CursosController@getEdit')->name('academico.cursos.getEdit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\CursosController@putEdit')->name('academico.cursos.putEdit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\CursosController@postDelete')->name('academico.cursos.delete');
    });

    Route::group(['prefix' => 'matrizescurriculares', 'middleware' => ['vinculo']], function () {
        Route::get('/index/{id}', '\Modulos\Academico\Http\Controllers\MatrizesCurricularesController@getIndex')->name('academico.matrizescurriculares.index');
        Route::get('/create/{id}', '\Modulos\Academico\Http\Controllers\MatrizesCurricularesController@getCreate')->name('academico.matrizescurriculares.getCreate');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\MatrizesCurricularesController@postCreate')->name('academico.matrizescurriculares.postCreate');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\MatrizesCurricularesController@getEdit')->name('academico.matrizescurriculares.getEdit');
        Route::get('/anexo/{id}', '\Modulos\Academico\Http\Controllers\MatrizesCurricularesController@getMatrizAnexo')->name('academico.matrizescurriculares.getAnexo');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\MatrizesCurricularesController@putEdit')->name('academico.matrizescurriculares.putEdit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\MatrizesCurricularesController@postDelete')->name('academico.matrizescurriculares.delete');
    });

    Route::group(['prefix' => 'centros'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\CentrosController@getIndex')->name('academico.centros.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\CentrosController@getCreate')->name('academico.centros.getCreate');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\CentrosController@postCreate')->name('academico.centros.postCreate');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\CentrosController@getEdit')->name('academico.centros.getEdit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\CentrosController@putEdit')->name('academico.centros.putEdit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\CentrosController@postDelete')->name('academico.centros.delete');
    });

    Route::group(['prefix' => 'ofertascursos', 'middleware' => ['vinculo']], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\OfertasCursosController@getIndex')->name('academico.ofertascursos.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\OfertasCursosController@getCreate')->name('academico.ofertascursos.getCreate');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\OfertasCursosController@postCreate')->name('academico.ofertascursos.postCreate');
    });

    Route::group(['prefix' => 'grupos', 'middleware' => ['vinculo']], function () {
        Route::get('/index/{id}', '\Modulos\Academico\Http\Controllers\GruposController@getIndex')->name('academico.grupos.index');
        Route::get('/create/{id}', '\Modulos\Academico\Http\Controllers\GruposController@getCreate')->name('academico.grupos.getCreate');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\GruposController@postCreate')->name('academico.grupos.postCreate');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\GruposController@getEdit')->name('academico.grupos.getEdit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\GruposController@putEdit')->name('academico.grupos.putEdit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\GruposController@postDelete')->name('academico.grupos.delete');
    });

    Route::group(['prefix' => 'turmas', 'middleware' => ['vinculo']], function () {
        Route::get('/index/{id}', '\Modulos\Academico\Http\Controllers\TurmasController@getIndex')->name('academico.turmas.index');
        Route::get('/create/{id}', '\Modulos\Academico\Http\Controllers\TurmasController@getCreate')->name('academico.turmas.getCreate');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\TurmasController@postCreate')->name('academico.turmas.postCreate');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\TurmasController@getEdit')->name('academico.turmas.getEdit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\TurmasController@putEdit')->name('academico.turmas.putEdit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\TurmasController@postDelete')->name('academico.turmas.delete');
    });


    Route::group(['prefix' => 'tutoresgrupos', 'middleware' => ['vinculo']], function () {
        Route::get('/index/{id}', '\Modulos\Academico\Http\Controllers\TutoresGruposController@getIndex')->name('academico.tutoresgrupos.index');
        Route::get('/create/{id}', '\Modulos\Academico\Http\Controllers\TutoresGruposController@getCreate')->name('academico.tutoresgrupos.getCreate');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\TutoresGruposController@postCreate')->name('academico.tutoresgrupos.postCreate');
        Route::get('/alterartutor/{id}', '\Modulos\Academico\Http\Controllers\TutoresGruposController@getAlterarTutor')->name('academico.tutoresgrupos.getAlterarTutor');
        Route::put('/alterartutor/{id}', '\Modulos\Academico\Http\Controllers\TutoresGruposController@putAlterarTutor')->name('academico.tutoresgrupos.putAlterarTutor');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\TutoresGruposController@postDelete')->name('academico.tutoresgrupos.delete');
    });

    Route::group(['prefix' => 'disciplinas'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\DisciplinasController@getIndex')->name('academico.disciplinas.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\DisciplinasController@getCreate')->name('academico.disciplinas.getCreate');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\DisciplinasController@postCreate')->name('academico.disciplinas.postCreate');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\DisciplinasController@getEdit')->name('academico.disciplinas.getEdit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\DisciplinasController@putEdit')->name('academico.disciplinas.putEdit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\DisciplinasController@postDelete')->name('academico.disciplinas.delete');
    });


    Route::group(['prefix' => 'modulosmatrizes', 'middleware' => ['vinculo']], function () {
        Route::get('/index/{id}', '\Modulos\Academico\Http\Controllers\ModulosMatrizesController@getIndex')->name('academico.modulosmatrizes.index');
        Route::get('/create/{id}', '\Modulos\Academico\Http\Controllers\ModulosMatrizesController@getCreate')->name('academico.modulosmatrizes.getCreate');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\ModulosMatrizesController@postCreate')->name('academico.modulosmatrizes.postCreate');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\ModulosMatrizesController@getEdit')->name('academico.modulosmatrizes.getEdit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\ModulosMatrizesController@putEdit')->name('academico.modulosmatrizes.putEdit');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\ModulosMatrizesController@postDelete')->name('academico.modulosmatrizes.delete');
        Route::get('/gerenciardisciplinas/{id}', '\Modulos\Academico\Http\Controllers\ModulosMatrizesController@getGerenciarDisciplinas')->name('academico.modulosmatrizes.gerenciardisciplinas');
        Route::post('/adicionardisciplinas/{id}', '\Modulos\Academico\Http\Controllers\ModulosMatrizesController@postAdicionarDisciplinas')->name('academico.modulosmatrizes.postAdicionardisciplinas');
    });

//    Route::group(['prefix' => 'modulosdisciplinas'], function () {
//        Route::get('/index/{id}', '\Modulos\Academico\Http\Controllers\ModulosDisciplinasController@getIndex')->name('academico.modulosdisciplinas.index');
//        Route::get('/create/{id}', '\Modulos\Academico\Http\Controllers\ModulosDisciplinasController@getCreate')->name('academico.modulosdisciplinas.getCreate');
//        Route::post('/create', '\Modulos\Academico\Http\Controllers\ModulosDisciplinasController@postCreate')->name('academico.modulosdisciplinas.postCreate');
//        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\ModulosDisciplinasController@getEdit')->name('academico.modulosdisciplinas.getEdit');
//        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\ModulosDisciplinasController@putEdit')->name('academico.modulosdisciplinas.putEdit');
//        Route::post('/delete', '\Modulos\Academico\Http\Controllers\ModulosDisciplinasController@postDelete')->name('academico.modulosdisciplinas.delete');
//    });

    Route::group(['prefix' => 'usuarioscursos'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\VinculosController@getIndex')->name('academico.vinculos.index');
        Route::get('/vinculos/{id}', '\Modulos\Academico\Http\Controllers\VinculosController@getVinculos')->name('academico.vinculos.vinculos');
        Route::get('/create/{id}', '\Modulos\Academico\Http\Controllers\VinculosController@getCreate')->name('academico.vinculos.getCreate');
        Route::post('/create/{id}', '\Modulos\Academico\Http\Controllers\VinculosController@postCreate')->name('academico.vinculos.postCreate');
        Route::post('/delete', '\Modulos\Academico\Http\Controllers\VinculosController@postDelete')->name('academico.vinculos.delete');
    });

    Route::group(['prefix' => 'tutores'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\TutoresController@getIndex')->name('academico.tutores.index');
        Route::get('/create/{id?}', '\Modulos\Academico\Http\Controllers\TutoresController@getCreate')->name('academico.tutores.getCreate')->middleware('verificapessoa');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\TutoresController@postCreate')->name('academico.tutores.postCreate');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\TutoresController@getEdit')->name('academico.tutores.getEdit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\TutoresController@putEdit')->name('academico.tutores.putEdit');
        Route::get('/show/{id}', '\Modulos\Academico\Http\Controllers\TutoresController@getShow')->name('academico.tutores.show');
    });

    Route::group(['prefix' => 'alunos'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\AlunosController@getIndex')->name('academico.alunos.index');
        Route::get('/create/{id?}', '\Modulos\Academico\Http\Controllers\AlunosController@getCreate')->name('academico.alunos.getCreate')->middleware('verificapessoa');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\AlunosController@postCreate')->name('academico.alunos.postCreate');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\AlunosController@getEdit')->name('academico.alunos.getEdit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\AlunosController@putEdit')->name('academico.alunos.putEdit');
        Route::get('/show/{id}', '\Modulos\Academico\Http\Controllers\AlunosController@getShow')->name('academico.alunos.show');
    });

    Route::group(['prefix' => 'professores'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\ProfessoresController@getIndex')->name('academico.professores.index');
        Route::get('/create/{id?}', '\Modulos\Academico\Http\Controllers\ProfessoresController@getCreate')->name('academico.professores.getCreate')->middleware('verificapessoa');
        Route::post('/create', '\Modulos\Academico\Http\Controllers\ProfessoresController@postCreate')->name('academico.professores.postCreate');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\ProfessoresController@getEdit')->name('academico.professores.getEdit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\ProfessoresController@putEdit')->name('academico.professores.putEdit');
        Route::get('/show/{id}', '\Modulos\Academico\Http\Controllers\ProfessoresController@getShow')->name('academico.professores.show');
    });

    Route::group(['prefix' => 'ofertasdisciplinas'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\OfertasDisciplinasController@getIndex')->name('academico.ofertasdisciplinas.index');
        Route::get('/create', '\Modulos\Academico\Http\Controllers\OfertasDisciplinasController@getCreate')->name('academico.ofertasdisciplinas.getCreate');
    });

    Route::group(['prefix' => 'matricularalunocurso'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\MatriculaCursoController@getIndex')->name('academico.matricularalunocurso.index');
        Route::get('/create/{id}', '\Modulos\Academico\Http\Controllers\MatriculaCursoController@getCreate')->name('academico.matricularalunocurso.getCreate');
        Route::post('/create/{id}', '\Modulos\Academico\Http\Controllers\MatriculaCursoController@postCreate')->name('academico.matricularalunocurso.postCreate');
        Route::get('/show/{id}', '\Modulos\Academico\Http\Controllers\MatriculaCursoController@getShow')->name('academico.matricularalunocurso.show');
    });

    Route::group(['prefix' => 'matricularalunodisciplina'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\MatriculasOfertasDisciplinasController@getIndex')->name('academico.matriculasofertasdisciplinas.index');
        Route::get('/show/{id}', '\Modulos\Academico\Http\Controllers\MatriculasOfertasDisciplinasController@getShow')->name('academico.matriculasofertasdisciplinas.show');
    });

    Route::group(['prefix' => 'lancamentostccs'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\LancamentosTccsController@getIndex')->name('academico.lancamentostccs.index');
        Route::get('/alunosturma/{id}', '\Modulos\Academico\Http\Controllers\LancamentosTccsController@getAlunosTurma')->name('academico.lancamentostccs.getAlunosturma');
        Route::get('/create/{idAluno}/{idTurma}', '\Modulos\Academico\Http\Controllers\LancamentosTccsController@getCreate')->name('academico.lancamentostccs.getCreate');
        Route::post('/create/{idTurma}', '\Modulos\Academico\Http\Controllers\LancamentosTccsController@postCreate')->name('academico.lancamentostccs.postCreate');
        Route::get('/edit/{id}', '\Modulos\Academico\Http\Controllers\LancamentosTccsController@getEdit')->name('academico.lancamentostccs.getEdit');
        Route::put('/edit/{id}', '\Modulos\Academico\Http\Controllers\LancamentosTccsController@putEdit')->name('academico.lancamentostccs.putEdit');
    });

    Route::group(['prefix' => 'conclusaocurso'], function () {
        Route::get('/index', '\Modulos\Academico\Http\Controllers\ConclusaoCursoController@getIndex')->name('academico.conclusaocurso.index');
    });

    //Rotas de funções assíncronas
    Route::group(['prefix' => 'async'], function () {
        Route::group(['prefix' => 'matrizescurriculares'], function () {
            Route::get('/findallbycurso/{id}', '\Modulos\Academico\Http\Controllers\Async\MatrizesCurriculares@getFindallbycurso')
                ->name('academico.async.matrizescurriculares.findallbycurso');
        });

        Route::group(['prefix' => 'turmas'], function () {
            Route::get('/findallbyofertacurso/{id}', '\Modulos\Academico\Http\Controllers\Async\Turmas@getFindallbyofertacurso')
                ->name('academico.async.turmas.findallbyofertacurso');
            Route::get('/findallbyofertacursowithoutambiente/{id}', '\Modulos\Academico\Http\Controllers\Async\Turmas@getFindallbyofertacursoWithoutAmbiente')
                ->name('academico.async.turmas.findallbyofertacursowithoutambiente');
        });

        Route::group(['prefix' => 'polos'], function () {
            Route::get('/findallbyofertacurso/{id}', '\Modulos\Academico\Http\Controllers\Async\Polos@getFindallbyofertacurso')
                ->name('academico.async.polos.findallbyofertacurso');
        });

        Route::group(['prefix' => 'ofertascursos'], function () {
            Route::get('/findallbycurso/{id}', '\Modulos\Academico\Http\Controllers\Async\OfertasCursos@getFindallbycurso')
                ->name('academico.async.ofertascursos.findallbycurso');
            Route::get('/findallbycursowithoutpresencial/{id}', '\Modulos\Academico\Http\Controllers\Async\OfertasCursos@getFindallbycursowithoutpresencial')
                ->name('academico.async.ofertascursos.findallbycursowithoutpresencial');
        });        

        Route::group(['prefix' => 'disciplinas'], function () {
            Route::get('/findbynome/{matriz}/{nome}/{modulo}', '\Modulos\Academico\Http\Controllers\Async\Disciplinas@getFindByNome');
        });

        Route::group(['prefix' => 'modulosdisciplinas'], function () {
            Route::get('/getalldisciplinasbymodulo/{modulo}', '\Modulos\Academico\Http\Controllers\Async\ModulosDisciplinas@getAllDisciplinasByModulo');
            Route::get('/verifydisciplina/{modulo}', '\Modulos\Academico\Http\Controllers\Async\ModulosDisciplinas@verifyDisciplinas');
            Route::post('/adicionardisciplina/', '\Modulos\Academico\Http\Controllers\Async\ModulosDisciplinas@postAdicionarDisciplina');
            Route::post('/deletardisciplina/', '\Modulos\Academico\Http\Controllers\Async\ModulosDisciplinas@postDeletarDisciplina');
        });

        Route::group(['prefix' => 'modulosmatriz'], function () {
            Route::get('/findallbymatriz/{id}', '\Modulos\Academico\Http\Controllers\Async\ModuloMatriz@getFindallbymatriz');
        });

        Route::group(['prefix' => 'ofertasturma'], function () {
            Route::get('/ofertabyturma/{id}', '\Modulos\Academico\Http\Controllers\Async\OfertasCursos@getOfertabyturma');
        });

        Route::group(['prefix' => 'ofertasdisciplinas'], function () {
            Route::get('/findall', '\Modulos\Academico\Http\Controllers\Async\OfertaDisciplina@getFindall');
            Route::post('/oferecerdisciplina', '\Modulos\Academico\Http\Controllers\Async\OfertaDisciplina@postOferecerdisciplina');
            Route::post('/deletarofertadisciplina', '\Modulos\Academico\Http\Controllers\Async\OfertaDisciplina@postDeletarofertadisciplina');
        });

        Route::group(['prefix' => 'matriculasofertasdisciplinas'], function () {
            Route::get('/findalldisciplinascursadasbyalunoturmaperiodo/{one}/{two}/{three}', '\Modulos\Academico\Http\Controllers\Async\MatriculaOfertaDisciplina@getFindAllDisciplinasCursadasByAlunoTurmaPeriodo');
            Route::get('/findalldisciplinasnotcursadasbyalunoturmaperiodo/{one}/{two}/{three}', '\Modulos\Academico\Http\Controllers\Async\MatriculaOfertaDisciplina@getFindAllDisciplinasNotCursadasByAlunoTurmaPeriodo');
            Route::post('/matricular', '\Modulos\Academico\Http\Controllers\Async\MatriculaOfertaDisciplina@postMatricularAlunoDisciplinas');
        });

        Route::group(['prefix' => 'grupos'], function () {
            Route::get('/findallbyturmapolo/{one}/{two}', '\Modulos\Academico\Http\Controllers\Async\Grupos@getFindallbyturmapolo');
        });

        Route::group(['prefix' => 'grupos'], function () {
            Route::get('/findallbyturma/{id}', '\Modulos\Academico\Http\Controllers\Async\Grupos@getFindallbyturma');
        });

        Route::group(['prefix' => 'tutores'], function () {
            Route::get('/findallbygrupo/{id}', '\Modulos\Academico\Http\Controllers\Async\Tutores@getFindallbygrupo');
        });

        Route::group(['prefix' => 'periodosletivos'], function () {
            Route::get('/findallbyturma/{id}', '\Modulos\Academico\Http\Controllers\Async\PeriodoLetivo@getFindallbyturma');
        });

        Route::group(['prefix' => 'professores'], function () {
            Route::get('/findall', '\Modulos\Academico\Http\Controllers\Async\Professor@getFindall');
        });
        
        Route::group(['prefix' => 'conclusaocurso'], function () {
            Route::get('/findallalunosaptosounao', '\Modulos\Academico\Http\Controllers\Async\ConclusaoCurso@getAllalunosaptosounao'); 
        });
    });
});
