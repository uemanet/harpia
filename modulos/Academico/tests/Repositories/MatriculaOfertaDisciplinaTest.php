<?php

use Tests\ModulosTestCase;
use Tests\Helpers\Reflection;
use Stevebauman\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Geral\Repositories\DocumentoRepository;
use Modulos\Academico\Models\MatriculaOfertaDisciplina;
use Modulos\Academico\Repositories\MatriculaOfertaDisciplinaRepository;

class MatriculaOfertaDisciplinaTest extends ModulosTestCase
{
    use Reflection;
    protected $docrepo;

    public function setUp()
    {
        parent::setUp();
        $this->repo = $this->app->make(MatriculaOfertaDisciplinaRepository::class);
        $this->docrepo = $this->app->make(DocumentoRepository::class);

        $this->table = 'acd_matriculas_ofertas_disciplinas';
    }

    public function testDeleteMatricula()
    {
        $data = factory(MatriculaOfertaDisciplina::class)->create();
        $response = $this->repo->deleteMatricula(['ofd_id' => $data->mof_ofd_id, 'mat_id' => $data->mof_mat_id]);
        $this->assertEquals('success', $response['type']);

        $data = factory(MatriculaOfertaDisciplina::class)->create(['mof_situacao_matricula' => 'cancelada']);
        $response = $this->repo->deleteMatricula(['ofd_id' => $data->mof_ofd_id, 'mat_id' => $data->mof_mat_id]);
        $this->assertEquals('error', $response['type']);

        $data = factory(MatriculaOfertaDisciplina::class)->create(['mof_nota1' => 7.0]);
        $response = $this->repo->deleteMatricula(['ofd_id' => $data->mof_ofd_id, 'mat_id' => $data->mof_mat_id]);
        $this->assertEquals('error', $response['type']);


        $response = $this->repo->deleteMatricula(['ofd_id' => 1000, 'mat_id' => 1000]);
        $this->assertEquals('error', $response['type']);

    }

    public function testCreate()
    {
        $oferta = factory(\Modulos\Academico\Models\OfertaDisciplina::class)->create();
        $matricula = factory(\Modulos\Academico\Models\Matricula::class)->create();

        $response = $this->repo->create(['mof_mat_id' => $matricula->mat_id,
            'mof_ofd_id' => $oferta->ofd_id,
            'mof_tipo_matricula' => 'matriculacomum',
            'mof_situacao_matricula' => 'cursando']);

        $this->assertInstanceOf(MatriculaOfertaDisciplina::class, $response);

        $this->assertArrayHasKey('mof_id', $response);
        $this->assertNotEmpty($response);
    }

    public function testFind()
    {
        $entry = factory(MatriculaOfertaDisciplina::class)->create();
        $id = $entry->mof_id;
        $fromRepository = $this->repo->find($id);

        $this->assertInstanceOf(MatriculaOfertaDisciplina::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepository->toArray());

        $this->assertEquals($entry->toArray(), $fromRepository->toArray());
    }

    public function testLists()
    {
        $entries = factory(MatriculaOfertaDisciplina::class, 2)->create();

        $model = new MatriculaOfertaDisciplina();
        $expected = $model->pluck('mof_tipo_matricula', 'mof_id');
        $fromRepository = $this->repo->lists('mof_id', 'mof_tipo_matricula');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        factory(MatriculaOfertaDisciplina::class)->create([
            'mof_tipo_matricula' => 'matriculacomum'
        ]);

        $searchResult = $this->repo->search(array(['mof_tipo_matricula', '=', 'matriculacomum']));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(MatriculaOfertaDisciplina::class)->create([
            'mof_tipo_matricula' => 'matriculacomum'
        ]);

        $searchResult = $this->repo->search(array(['mof_tipo_matricula', '=', 'matriculacomum']), ['mof_id']);

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testAll()
    {
        // With empty database
        $collection = $this->repo->all();

        $this->assertEquals(0, $collection->count());

        // Non-empty database
        $created = factory(MatriculaOfertaDisciplina::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(MatriculaOfertaDisciplina::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new MatriculaOfertaDisciplina();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(MatriculaOfertaDisciplina::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(MatriculaOfertaDisciplina::class, 2)->create();

        $sort = [
            'field' => 'mof_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->mof_id);
    }

    public function testPaginateWithSearch()
    {
        $entry = factory(MatriculaOfertaDisciplina::class)->create([
            'mof_tipo_matricula' => 'matriculacomun'
        ]);

        $search = [
            [
                'field' => 'mof_tipo_matricula',
                'type' => '=',
                'term' => 'matriculacomun'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('matriculacomun', $response->first()->mof_tipo_matricula);
    }

    public function testGetAllAlunosBySituacaoWithDoc()
    {
        $turma = factory(\Modulos\Academico\Models\Turma::class)->create();
        $matriculas = factory(\Modulos\Academico\Models\Matricula::class, 10)->create(['mat_trm_id' => $turma->trm_id]);
        $ofertaDisciplina = factory(\Modulos\Academico\Models\OfertaDisciplina::class)->create(['ofd_trm_id' => $turma->trm_id]);

        foreach ($matriculas as $matricula) {
            factory(MatriculaOfertaDisciplina::class)->create(['mof_mat_id' => $matricula->mat_id, 'mof_ofd_id' => $ofertaDisciplina, 'mof_tipo_matricula' => 'matriculacomun', 'mof_situacao_matricula' => 'cursando']);
            $rg = $this->docrepo->create(['doc_pes_id' => $matricula->aluno->pessoa->pes_id, 'doc_tpd_id' => 2, 'doc_conteudo' => '123456', 'doc_data_expedicao' => '10/10/2000']);
            $cpf = $this->docrepo->create(['doc_pes_id' => $matricula->aluno->pessoa->pes_id, 'doc_tpd_id' => 1, 'doc_conteudo' => '123456']);
            $polo = $matricula->mat_pol_id;
        }

        $response = $this->repo->getAllAlunosBySituacao($turma->trm_id, $ofertaDisciplina->ofd_id, 'cursando', $polo);

        $this->assertNotEmpty($response);
    }

    public function testGetAllAlunosBySituacaoWithoutDoc()
    {
        $turma = factory(\Modulos\Academico\Models\Turma::class)->create();
        $matriculas = factory(\Modulos\Academico\Models\Matricula::class, 10)->create(['mat_trm_id' => $turma->trm_id]);
        $ofertaDisciplina = factory(\Modulos\Academico\Models\OfertaDisciplina::class)->create(['ofd_trm_id' => $turma->trm_id]);

        foreach ($matriculas as $matricula) {
            factory(MatriculaOfertaDisciplina::class)->create(['mof_mat_id' => $matricula->mat_id, 'mof_ofd_id' => $ofertaDisciplina, 'mof_tipo_matricula' => 'matriculacomun', 'mof_situacao_matricula' => 'cursando']);
            $polo = $matricula->mat_pol_id;
        }

        $response = $this->repo->getAllAlunosBySituacao($turma->trm_id, $ofertaDisciplina->ofd_id, 'cursando', $polo);

        $this->assertNotEmpty($response);
    }


    private function mockConf()
    {
        $matriculaoferta = factory(MatriculaOfertaDisciplina::class)->create(['mof_situacao_matricula' => 'cursando']);
        $cursoId = $matriculaoferta->matriculaCurso->turma->ofertacurso->curso->crs_id;
        factory(\Modulos\Academico\Models\ConfiguracaoCurso::class)->create(['cfc_crs_id' => $cursoId, 'cfc_nome' => 'media_min_aprovacao', 'cfc_valor' => 7]);
        factory(\Modulos\Academico\Models\ConfiguracaoCurso::class)->create(['cfc_crs_id' => $cursoId, 'cfc_nome' => 'media_min_final', 'cfc_valor' => 5]);
        factory(\Modulos\Academico\Models\ConfiguracaoCurso::class)->create(['cfc_crs_id' => $cursoId, 'cfc_nome' => 'media_min_aprovacao_final', 'cfc_valor' => 5]);
        factory(\Modulos\Academico\Models\ConfiguracaoCurso::class)->create(['cfc_crs_id' => $cursoId, 'cfc_nome' => 'modo_recuperacao', 'cfc_valor' => 'substituir_media_final']);
        factory(\Modulos\Academico\Models\ConfiguracaoCurso::class)->create(['cfc_crs_id' => $cursoId, 'cfc_nome' => 'conceitos_aprovacao', 'cfc_valor' => '["Bom","Muito Bom","Excelente"]']);

        return $matriculaoferta;
    }

    public function testUpdate()
    {
        $matriculaOferta = $this->mockConf();

        $matriculaOferta->mof_nota1 = 7;
        $matriculaOferta->mof_nota2 = 7;
        $matriculaOferta->mof_nota3 = 7;

        $response = $this->repo->update(['mof_nota1' => 7,
                                         'mof_nota2' => 7,
                                         'mof_nota3' => 7,
                                         'mof_final' => '',
                                         'mof_recuperacao' => '',
                                         'mof_conceito'=> null], $matriculaOferta->mof_id);


        $this->assertEquals($response, 1);
    }

    public function testGetAllMatriculasByAluno()
    {
        $matriculaoferta = factory(MatriculaOfertaDisciplina::class)->create();

        $response = $this->repo->getAllMatriculasByAluno($matriculaoferta->matriculaCurso->aluno->alu_id);

        $this->assertNotEmpty($response);
        $this->assertCount(1, $response);
    }

    public function testGetAllMatriculasByAlunoModuloMatriz()
    {
        $data = $this->mock();

        $matriculaoferta = $data[0];
        $modulomatriz = $data[1];

        $response = $this->repo->getAllMatriculasByAlunoModuloMatriz($matriculaoferta->matriculaCurso->aluno->alu_id, $modulomatriz->mdo_id);

        $this->assertNotEmpty($response);
    }

    public function testPaginateWithSearchAndOrder()
    {
        $this->mock();

        $sort = [
            'field' => 'pes_nome',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'mof_id',
                'type' => '>',
                'term' => '1'
            ]
        ];

        $response = $this->repo->paginate($sort, $search);

        $this->assertGreaterThan(1, $response->total());

        $this->assertEquals(2, count($response));
    }

    public function testPaginateWithSearchAndOrderByCpf()
    {
        $this->mock();

        $sort = [
            'field' => 'pes_nome',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'pes_cpf',
                'type' => 'like',
                'term' => '53743639634'
            ],
            [
                'field' => 'pes_nome',
                'type' => 'like',
                'term' => 'Empty'
            ]
        ];

        $response = $this->repo->paginate($sort, $search);

        $this->assertEmpty($response->total());
    }

    public function testGetMatriculasOfertasDisciplinasByMatricula()
    {
        $data = $this->mock();

        $matriculaoferta = $data[0];

        $response = $this->repo->getMatriculasOfertasDisciplinasByMatricula($matriculaoferta->matriculaCurso->mat_id, []);

        $this->assertNotEmpty($response);

        $this->assertEquals($response[0]->mof_mat_id, $matriculaoferta->mof_mat_id);
    }

    public function testGetDisciplinasCursadasByAluno()
    {
        $data = $this->mock();

        $matriculaoferta = $data[0];

        $response = $this->repo->getDisciplinasCursadasByAluno($matriculaoferta->matriculaCurso->aluno->alu_id, [
            'ofd_per_id' => $matriculaoferta->ofertaDisciplina->ofd_per_id,
            'ofd_trm_id' => $matriculaoferta->ofertaDisciplina->ofd_trm_id,
            'mof_situacao_matricula' => ['cursando']
        ]);

        $this->assertNotEmpty($response);
    }

    public function testeVerifyIfAlunoIsMatriculadoInDisciplinaOferecida()
    {
        $matricula = factory(Modulos\Academico\Models\Matricula::class)->create();

        $ofertaDisciplina = factory(Modulos\Academico\Models\OfertaDisciplina::class)->create();

        // matricular aluno nessa oferta
        $matriculaDisciplina = factory(MatriculaOfertaDisciplina::class)->create([
            'mof_mat_id' => $matricula->mat_id,
            'mof_ofd_id' => $ofertaDisciplina->ofd_id
        ]);

        $data = $matriculaDisciplina->toArray();

        $this->assertInstanceOf(MatriculaOfertaDisciplina::class, $matriculaDisciplina);

        $this->assertEquals($matricula->mat_id, $data['mof_mat_id']);
        $this->assertEquals($ofertaDisciplina->ofd_id, $data['mof_ofd_id']);
    }

    public function testGetDisciplinasOfertadasNotCursadasByAluno()
    {
        $data = $this->mock();

        $matriculaoferta = $data[0];

        $response = $this->repo->getDisciplinasOfertadasNotCursadasByAluno($matriculaoferta->matriculaCurso->mat_alu_id, $matriculaoferta->matriculaCurso->mat_trm_id, $matriculaoferta->ofertaDisciplina->ofd_per_id);


        $this->assertNotEmpty($response);
    }

    public function testCreateMatricula()
    {
        $data = $this->mock();

        list(, , $ofertaDisciplina, $matriculaCurso) = $data;

        $matricula = factory(\Modulos\Academico\Models\Matricula::class)->create(['mat_trm_id' => $matriculaCurso->mat_trm_id, 'mat_pol_id' => $matriculaCurso->mat_pol_id, 'mat_grp_id' => $matriculaCurso->mat_grp_id, 'mat_situacao' => 'cursando']);

        $response = $this->repo->createMatricula(['mat_id' => $matricula->mat_id, 'ofd_id' => $ofertaDisciplina->ofd_id]);

        $this->assertNotEmpty($response);
    }

    public function testCreateMatriculaAlunoSemPreRequisitos()
    {
        $data = $this->mock();

        list(, $moduloMatriz, $ofertaDisciplina, $matriculaCurso, $modulodisciplina) = $data;

        $modulo = factory(\Modulos\Academico\Models\ModuloMatriz::class)->create(['mdo_mtc_id' => $moduloMatriz->mdo_mtc_id, 'mdo_nome' => 'Módulo de Teste']);
        $modulodisciplina = factory(\Modulos\Academico\Models\ModuloDisciplina::class)->create(['mdc_mdo_id' => $modulo->mdo_id, 'mdc_pre_requisitos' => "[".$modulodisciplina->mdc_id."]"]);
        $oferta = factory(\Modulos\Academico\Models\OfertaDisciplina::class)->create(['ofd_qtd_vagas' => 100, 'ofd_mdc_id' => $modulodisciplina->mdc_id]);
        $matricula = factory(\Modulos\Academico\Models\Matricula::class)->create(['mat_trm_id' => $matriculaCurso->mat_trm_id, 'mat_pol_id' => $matriculaCurso->mat_pol_id, 'mat_grp_id' => $matriculaCurso->mat_grp_id, 'mat_situacao' => 'cursando']);

        $response = $this->repo->createMatricula(['mat_id' => $matricula->mat_id, 'ofd_id' => $oferta->ofd_id]);

        $this->assertNotEmpty($response);
        $this->assertEquals($response['type'], 'error');
        $this->assertEquals($response['message'], 'Aluno possui pre-requisitos não satisfeitos');
    }

    public function testCreateMatriculaAlunoReprovadoNoCurso()
    {
        $data = $this->mock();

        list(, , $ofertaDisciplina, $matriculaCurso) = $data;

        $matricula = factory(\Modulos\Academico\Models\Matricula::class)->create(['mat_trm_id' => $matriculaCurso->mat_trm_id, 'mat_pol_id' => $matriculaCurso->mat_pol_id, 'mat_grp_id' => $matriculaCurso->mat_grp_id, 'mat_situacao' => 'reprovado']);

        $response = $this->repo->createMatricula(['mat_id' => $matricula->mat_id, 'ofd_id' => $ofertaDisciplina->ofd_id]);


        $this->assertEquals($response['type'], 'error');
        $this->assertEquals($response['message'], 'Aluno não está cursando o curso');
        $this->assertNotEmpty($response);
    }

    public function testCreateMatriculaDisciplinaSemVagas()
    {
        $oferta = factory(\Modulos\Academico\Models\OfertaDisciplina::class)->create(['ofd_qtd_vagas' => 0]);
        $matriculaoferta = factory(MatriculaOfertaDisciplina::class)->create(['mof_tipo_matricula' => 'matriculacomum', 'mof_situacao_matricula' => 'aprovado_media', 'mof_ofd_id' => $oferta->ofd_id]);

        $response = $this->repo->createMatricula(['mat_id' => $matriculaoferta->mof_mat_id, 'ofd_id' => $matriculaoferta->mof_ofd_id]);

        $this->assertNotEmpty($response);

        $this->assertEquals($response['type'], 'error');
        $this->assertEquals($response['message'], 'Sem vagas disponiveis');
    }

    public function testCreateMatriculaAlunoReprovadoDisciplina()
    {
        $matriculaoferta = factory(MatriculaOfertaDisciplina::class)->create(['mof_tipo_matricula' => 'matriculacomum', 'mof_situacao_matricula' => 'reprovado_media']);

        $response = $this->repo->createMatricula(['mat_id' => $matriculaoferta->mof_mat_id, 'ofd_id' => $matriculaoferta->mof_ofd_id]);

        $this->assertEquals($response['type'], 'error');
        $this->assertEquals($response['message'], 'Aluno está reprovado nesta oferta de disciplina');
    }

    public function testCreateMatriculaAlunoAprovadoDisciplina()
    {
        $matriculaoferta = factory(MatriculaOfertaDisciplina::class)->create(['mof_tipo_matricula' => 'matriculacomum', 'mof_situacao_matricula' => 'aprovado_media']);

        $response = $this->repo->createMatricula(['mat_id' => $matriculaoferta->mof_mat_id, 'ofd_id' => $matriculaoferta->mof_ofd_id]);

        $this->assertNotEmpty($response);

        $this->assertEquals($response['type'], 'error');
        $this->assertEquals($response['message'], 'Aluno já aprovado nessa disciplina.');
    }

    public function testCreateMatriculaDuplicada()
    {
        $data = $this->mock();

        list(, , $ofertaDisciplina, $matriculaCurso) = $data;

        $matricula = factory(\Modulos\Academico\Models\Matricula::class)->create(['mat_trm_id' => $matriculaCurso->mat_trm_id, 'mat_pol_id' => $matriculaCurso->mat_pol_id, 'mat_grp_id' => $matriculaCurso->mat_grp_id, 'mat_situacao' => 'cursando']);

        $this->repo->createMatricula(['mat_id' => $matricula->mat_id, 'ofd_id' => $ofertaDisciplina->ofd_id]);
        $response = $this->repo->createMatricula(['mat_id' => $matricula->mat_id, 'ofd_id' => $ofertaDisciplina->ofd_id]);

        $this->assertEquals($response['type'], 'error');
        $this->assertEquals($response['message'], 'Aluno está cursando essa disciplina');
        $this->assertNotEmpty($response);
    }

    public function testGetAlunosMatriculasLote()
    {
        $turma = factory(\Modulos\Academico\Models\Turma::class)->create();
        $ofertaDisciplina = factory(\Modulos\Academico\Models\OfertaDisciplina::class)->create(['ofd_trm_id' => $turma->trm_id]);

        //esta matrícula em disciplina testa o caso em que o aluno já tem uma matrícula nessa disciplina e foi reprovado por média
        $matricula = factory(\Modulos\Academico\Models\Matricula::class)->create(['mat_trm_id' => $turma->trm_id]);
        $matriculaOferta = factory(MatriculaOfertaDisciplina::class)->create(['mof_mat_id' => $matricula->mat_id, 'mof_ofd_id' => $ofertaDisciplina->ofd_id, 'mof_tipo_matricula' => 'matriculacomun', 'mof_situacao_matricula' => 'reprovado_media']);

        //esta matrícula em disciplina testa o caso em que o aluno já tem uma matrícula nessa disciplina e foi aprovado por média
        $matricula = factory(\Modulos\Academico\Models\Matricula::class)->create(['mat_trm_id' => $turma->trm_id]);
        $matriculaOferta = factory(MatriculaOfertaDisciplina::class)->create(['mof_mat_id' => $matricula->mat_id, 'mof_ofd_id' => $ofertaDisciplina->ofd_id, 'mof_tipo_matricula' => 'matriculacomun', 'mof_situacao_matricula' => 'aprovado_media']);

        //esta matrícula em disciplina testa o caso em que o aluno já tem uma matrícula nessa disciplina e está cursando a mesma
        $matricula = factory(\Modulos\Academico\Models\Matricula::class)->create(['mat_trm_id' => $turma->trm_id]);
        $matriculaOferta = factory(MatriculaOfertaDisciplina::class)->create(['mof_mat_id' => $matricula->mat_id, 'mof_ofd_id' => $ofertaDisciplina->ofd_id, 'mof_tipo_matricula' => 'matriculacomun', 'mof_situacao_matricula' => 'cursando']);

        //esta matrícula em disciplina testa o caso em que o aluno já tem uma matrícula nessa disciplina com status de matrícula cancelado
        $matricula = factory(\Modulos\Academico\Models\Matricula::class)->create(['mat_trm_id' => $turma->trm_id]);
        $matriculaOferta = factory(MatriculaOfertaDisciplina::class)->create(['mof_mat_id' => $matricula->mat_id, 'mof_ofd_id' => $ofertaDisciplina->ofd_id, 'mof_tipo_matricula' => 'matriculacomun', 'mof_situacao_matricula' => 'cancelado']);

        //este é para o caso em que o aluno não tem nenhuma matrícula na oferta disciplina em questão e portando ele está apto a se matricular nessa disciplina
        $matricula = factory(\Modulos\Academico\Models\Matricula::class)->create(['mat_trm_id' => $turma->trm_id]);

        $response = $this->repo->getAlunosMatriculasLote(['ofd_id' => $ofertaDisciplina->ofd_id, 'trm_id' => $turma->trm_id]);

        $this->assertArrayHasKey('nao_matriculados', $response);
        $this->assertArrayHasKey('cursando', $response);
        $this->assertArrayHasKey('aprovados', $response);
        $this->assertArrayHasKey('reprovados', $response);
        $this->assertNotEmpty($response);
    }

    public function testGetAlunosMatriculasLoteComPolo()
    {
        $turma = factory(\Modulos\Academico\Models\Turma::class)->create();
        $ofertaDisciplina = factory(\Modulos\Academico\Models\OfertaDisciplina::class)->create(['ofd_trm_id' => $turma->trm_id]);

        //este é para o caso em que o aluno não tem nenhuma matrícula na oferta disciplina em questão e portando ele está apto a se matricular nessa disciplina
        $matricula = factory(\Modulos\Academico\Models\Matricula::class)->create(['mat_trm_id' => $turma->trm_id]);

        $response = $this->repo->getAlunosMatriculasLote(['ofd_id' => $ofertaDisciplina->ofd_id, 'trm_id' => $turma->trm_id, 'pol_id' => $matricula->mat_pol_id]);

        $this->assertArrayHasKey('nao_matriculados', $response);
        $this->assertArrayHasKey('cursando', $response);
        $this->assertArrayHasKey('aprovados', $response);
        $this->assertArrayHasKey('reprovados', $response);
        $this->assertNotEmpty($response);
    }

    public function testGetAlunosMatriculasLoteSemPreRequisitos()
    {
        $data = $this->mock();

        list(, $moduloMatriz, $ofertaDisciplina, $matriculaCurso, $modulodisciplina) = $data;

        $modulo = factory(\Modulos\Academico\Models\ModuloMatriz::class)->create(['mdo_mtc_id' => $moduloMatriz->mdo_mtc_id, 'mdo_nome' => 'Módulo de Teste']);
        $modulodisciplina = factory(\Modulos\Academico\Models\ModuloDisciplina::class)->create(['mdc_mdo_id' => $modulo->mdo_id, 'mdc_pre_requisitos' => "[".$modulodisciplina->mdc_id."]"]);
        $oferta = factory(\Modulos\Academico\Models\OfertaDisciplina::class)->create(['ofd_qtd_vagas' => 100, 'ofd_mdc_id' => $modulodisciplina->mdc_id]);
        $matricula = factory(\Modulos\Academico\Models\Matricula::class)->create(['mat_trm_id' => $matriculaCurso->mat_trm_id, 'mat_pol_id' => $matriculaCurso->mat_pol_id, 'mat_grp_id' => $matriculaCurso->mat_grp_id, 'mat_situacao' => 'cursando']);

        $response = $this->repo->getAlunosMatriculasLote(['ofd_id' => $oferta->ofd_id, 'trm_id' => $matricula->mat_trm_id, 'pol_id' => $matricula->mat_pol_id]);
        $this->assertArrayHasKey('nao_matriculados', $response);
        $this->assertArrayHasKey('cursando', $response);
        $this->assertArrayHasKey('aprovados', $response);
        $this->assertArrayHasKey('reprovados', $response);
        $this->assertNotEmpty($response);

        factory(MatriculaOfertaDisciplina::class)->create(['mof_mat_id' => $matricula->mat_id, 'mof_ofd_id' => $oferta->ofd_id, 'mof_tipo_matricula' => 'matriculacomun', 'mof_situacao_matricula' => 'cancelado']);
        $response = $this->repo->getAlunosMatriculasLote(['ofd_id' => $oferta->ofd_id, 'trm_id' => $matricula->mat_trm_id, 'pol_id' => $matricula->mat_pol_id]);
        $this->assertArrayHasKey('nao_matriculados', $response);
        $this->assertArrayHasKey('cursando', $response);
        $this->assertArrayHasKey('aprovados', $response);
        $this->assertArrayHasKey('reprovados', $response);
        $this->assertNotEmpty($response);
    }

    public function testpaginateRequestByParametrosNoParemeters()
    {
        factory(\Modulos\Academico\Models\MatriculaOfertaDisciplina::class, 10)->create();

        $response = $this->repo->paginateRequestByParametros();

        $this->assertEmpty($response);
    }

    public function testpaginateRequestByParametrosNullParemeters()
    {
        $turma = factory(\Modulos\Academico\Models\Turma::class)->create();
        $matriculas = factory(\Modulos\Academico\Models\Matricula::class, 10)->create(['mat_trm_id' => $turma->trm_id]);
        $oferta = factory(\Modulos\Academico\Models\OfertaDisciplina::class)->create(['ofd_trm_id' => $turma->trm_id]);
        foreach ($matriculas as $matricula) {
            factory(MatriculaOfertaDisciplina::class)->create(['mof_mat_id' => $matricula->mat_id, 'mof_ofd_id' => $oferta->ofd_id]);
        }

        $response = $this->repo->paginateRequestByParametros(['trm_id' => null, 'ofd_id' => null]);

        $this->assertEmpty($response);
    }

    public function testpaginateRequestByParametros()
    {
        $turma = factory(\Modulos\Academico\Models\Turma::class)->create();
        $matriculas = factory(\Modulos\Academico\Models\Matricula::class, 10)->create(['mat_trm_id' => $turma->trm_id]);
        $oferta = factory(\Modulos\Academico\Models\OfertaDisciplina::class)->create(['ofd_trm_id' => $turma->trm_id]);
        foreach ($matriculas as $matricula) {
            factory(MatriculaOfertaDisciplina::class)->create(['mof_mat_id' => $matricula->mat_id, 'mof_ofd_id' => $oferta->ofd_id, 'mof_situacao_matricula' => 'cursando']);
        }

        $response = $this->repo->paginateRequestByParametros(['trm_id' => $turma->trm_id, 'ofd_id' => $oferta->ofd_id, 'mof_situacao_matricula' => 'cursando']);

        $this->assertNotEmpty($response);
    }

    public function testpaginateRequestByParametrosWithOrderAndSearch()
    {
        $turma = factory(\Modulos\Academico\Models\Turma::class)->create();
        $matriculas = factory(\Modulos\Academico\Models\Matricula::class, 10)->create(['mat_trm_id' => $turma->trm_id]);
        $oferta = factory(\Modulos\Academico\Models\OfertaDisciplina::class)->create(['ofd_trm_id' => $turma->trm_id]);
        foreach ($matriculas as $matricula) {
            factory(MatriculaOfertaDisciplina::class)->create(['mof_mat_id' => $matricula->mat_id, 'mof_ofd_id' => $oferta->ofd_id, 'mof_situacao_matricula' => 'cursando']);
        }

        $response = $this->repo->paginateRequestByParametros(['trm_id' => $turma->trm_id, 'ofd_id' => $oferta->ofd_id, 'mof_situacao_matricula' => 'cursando', 'sort' => 'asc', 'field' => 'pes_nome', 'pol_id' => $matriculas[0]->mat_pol_id]);

        $this->assertNotEmpty($response);
    }

    public function testcalculaNotasNumericaAprovadoMedia()
    {
        $matriculaOferta = $this->mockConf();

        $configsCurso = $matriculaOferta->ofertaDisciplina->turma->ofertaCurso->curso->configuracoes;

        $configuracoesCurso = $configsCurso->mapWithKeys(function ($item) {
            return [$item->cfc_nome => $item->cfc_valor];
        })->toArray();

        $response = $this->invokeMethod($this->repo, 'calculaNotas',
            [
                [
                    'mof_nota1' => 7,
                    'mof_nota2' => 7,
                    'mof_nota3' => 7,
                    'mof_final' => null,
                    'mof_recuperacao' => '',
                    'mof_conceito'=> null
                ], $configuracoesCurso
            ]);

        $this->assertEquals($response['mof_situacao_matricula'], 'aprovado_media');
    }

    public function testcalculaNotasNumericaAprovadoFinal()
    {
        $matriculaOferta = $this->mockConf();

        $configsCurso = $matriculaOferta->ofertaDisciplina->turma->ofertaCurso->curso->configuracoes;

        $configuracoesCurso = $configsCurso->mapWithKeys(function ($item) {
            return [$item->cfc_nome => $item->cfc_valor];
        })->toArray();

        $response = $this->invokeMethod($this->repo, 'calculaNotas',
            [
                [
                    'mof_nota1' => 7,
                    'mof_nota2' => 4,
                    'mof_nota3' => 4,
                    'mof_final' => 7,
                    'mof_recuperacao' => 5,
                    'mof_conceito'=> null
                ], $configuracoesCurso
            ]);


        $this->assertEquals($response['mof_situacao_matricula'], 'aprovado_final');
    }

    public function testcalculaNotasNumericaAprovadoRecuperacao()
    {
        $matriculaOferta = $this->mockConf();

        $configsCurso = $matriculaOferta->ofertaDisciplina->turma->ofertaCurso->curso->configuracoes;

        $configuracoesCurso = $configsCurso->mapWithKeys(function ($item) {
            return [$item->cfc_nome => $item->cfc_valor];
        })->toArray();

        $response = $this->invokeMethod($this->repo, 'calculaNotas',
            [
                [
                    'mof_nota1' => 7,
                    'mof_nota2' => 7,
                    'mof_nota3' => 4,
                    'mof_final' => null,
                    'mof_recuperacao' => 7,
                    'mof_conceito'=> null
                ], $configuracoesCurso
            ]);


        $this->assertEquals($response['mof_situacao_matricula'], 'aprovado_media');
    }

    public function testcalculaNotasNumericaAprovadoRecuperacaoSubstituirMenorNota()
    {
        $matriculaOferta = $this->mockConf();

        $configsCurso = $matriculaOferta->ofertaDisciplina->turma->ofertaCurso->curso->configuracoes;

        $configuracoesCurso = $configsCurso->mapWithKeys(function ($item) {
            return [$item->cfc_nome => $item->cfc_valor];
        })->toArray();

        $configuracoesCurso['modo_recuperacao'] = 'substituir_menor_nota';

        $response = $this->invokeMethod($this->repo, 'calculaNotas',
            [
                [
                    'mof_nota1' => 7,
                    'mof_nota2' => 7,
                    'mof_nota3' => 4,
                    'mof_final' => null,
                    'mof_recuperacao' => 7,
                    'mof_conceito'=> null
                ], $configuracoesCurso
            ]);


        $this->assertEquals($response['mof_situacao_matricula'], 'aprovado_media');
    }

    public function testcalculaNotasConceitoAprovadoConceito()
    {
        $matriculaOferta = $this->mockConf();

        $configsCurso = $matriculaOferta->ofertaDisciplina->turma->ofertaCurso->curso->configuracoes;

        $configuracoesCurso = $configsCurso->mapWithKeys(function ($item) {
            return [$item->cfc_nome => $item->cfc_valor];
        })->toArray();

        $response = $this->invokeMethod($this->repo, 'calculaNotas',
            [
                [
                    'mof_conceito'=> 'Bom'
                ], $configuracoesCurso
            ]);

        $this->assertEquals($response['mof_situacao_matricula'], 'aprovado_media');
    }

    public function testDelete()
    {
        $data = factory(MatriculaOfertaDisciplina::class)->create();
        $matriculaOfertaDisciplinaId = $data->mof_id;

        $response = $this->repo->delete($matriculaOfertaDisciplinaId);

        $this->assertEquals(1, $response);
    }

    private function mock()
    {
        $curso = factory(Modulos\Academico\Models\Curso::class)->create([
            'crs_nvc_id' => 1,
        ]);

        $matrizCurricular = factory(Modulos\Academico\Models\MatrizCurricular::class)->create([
            'mtc_crs_id' => $curso->crs_id
        ]);

        $oferta = factory(Modulos\Academico\Models\OfertaCurso::class)->create([
            'ofc_crs_id' => $curso->crs_id,
            'ofc_mtc_id' => $matrizCurricular->mtc_id,
        ]);

        $turma = factory(Modulos\Academico\Models\Turma::class)->create([
            'trm_ofc_id' => $oferta->ofc_id,
        ]);

        $polo = factory(Modulos\Academico\Models\Polo::class)->create();
        $oferta->polos()->attach($polo->pol_id);

        $grupo = factory(Modulos\Academico\Models\Grupo::class)->create([
            'grp_trm_id' => $turma->trm_id,
            'grp_pol_id' => $polo->pol_id
        ]);

        $moduloMatriz = factory(Modulos\Academico\Models\ModuloMatriz::class)->create([
            'mdo_mtc_id' => $matrizCurricular->mtc_id
        ]);

        $disciplina = factory(Modulos\Academico\Models\Disciplina::class)->create([
            'dis_nvc_id' => $curso->crs_nvc_id
        ]);

        $disciplina2 = factory(Modulos\Academico\Models\Disciplina::class)->create([
            'dis_nvc_id' => $curso->crs_nvc_id
        ]);

        $disciplina3 = factory(Modulos\Academico\Models\Disciplina::class)->create([
            'dis_nvc_id' => $curso->crs_nvc_id
        ]);

        $moduloDisciplina = factory(Modulos\Academico\Models\ModuloDisciplina::class)->create([
            'mdc_dis_id' => $disciplina->dis_id,
            'mdc_mdo_id' => $moduloMatriz->mdo_id,
            'mdc_tipo_disciplina' => 'obrigatoria'
        ]);

        $moduloDisciplina2 = factory(Modulos\Academico\Models\ModuloDisciplina::class)->create([
            'mdc_dis_id' => $disciplina2->dis_id,
            'mdc_mdo_id' => $moduloMatriz->mdo_id,
            'mdc_tipo_disciplina' => 'obrigatoria',
            'mdc_pre_requisitos' => json_encode(['mdc_id' => $moduloDisciplina->mdc_id])
        ]);

        $moduloDisciplina3 = factory(Modulos\Academico\Models\ModuloDisciplina::class)->create([
            'mdc_dis_id' => $disciplina3->dis_id,
            'mdc_mdo_id' => $moduloMatriz->mdo_id,
            'mdc_tipo_disciplina' => 'obrigatoria',
            'mdc_pre_requisitos' => json_encode(['mdc_id' => $moduloDisciplina->mdc_id])
        ]);

        $professor = factory(Modulos\Academico\Models\Professor::class)->create();

        $ofertaDisciplina = factory(Modulos\Academico\Models\OfertaDisciplina::class)->create([
            'ofd_mdc_id' => $moduloDisciplina->mdc_id,
            'ofd_trm_id' => $turma->trm_id,
            'ofd_per_id' => $turma->trm_per_id,
            'ofd_prf_id' => $professor->prf_id,
            'ofd_tipo_avaliacao' => 'numerica',
            'ofd_qtd_vagas' => 500
        ]);

        $ofertaDisciplinaCancelado = factory(Modulos\Academico\Models\OfertaDisciplina::class)->create([
            'ofd_mdc_id' => $moduloDisciplina2->mdc_id,
            'ofd_trm_id' => $turma->trm_id,
            'ofd_per_id' => $turma->trm_per_id,
            'ofd_prf_id' => $professor->prf_id,
            'ofd_tipo_avaliacao' => 'numerica',
            'ofd_qtd_vagas' => 0
        ]);

        $ofertaDisciplinaReprovado = factory(Modulos\Academico\Models\OfertaDisciplina::class)->create([
            'ofd_mdc_id' => $moduloDisciplina3->mdc_id,
            'ofd_trm_id' => $turma->trm_id,
            'ofd_per_id' => $turma->trm_per_id,
            'ofd_prf_id' => $professor->prf_id,
            'ofd_tipo_avaliacao' => 'numerica',
            'ofd_qtd_vagas' => 0
        ]);

        factory(\Modulos\Geral\Models\Titulacao::class, 7)->create();

        $titulacaoProfessor = factory(Modulos\Geral\Models\TitulacaoInformacao::class)->create([
            'tin_pes_id' => $professor->pessoa->pes_id,
            'tin_tit_id' => random_int(2, 7),
        ]);

        $aluno = factory(\Modulos\Academico\Models\Aluno::class)->create();

        $matricula = factory(\Modulos\Academico\Models\Matricula::class)->create([
            'mat_alu_id' => $aluno->alu_id,
            'mat_trm_id' => $turma->trm_id,
            'mat_pol_id' => $polo->pol_id,
            'mat_grp_id' => $grupo->grp_id
        ]);

        $matriculaOfertaDisciplina = factory(Modulos\Academico\Models\MatriculaOfertaDisciplina::class)->create([
            'mof_mat_id' => $matricula->mat_id,
            'mof_ofd_id' => $ofertaDisciplina->ofd_id,
            'mof_tipo_matricula' => 'matriculacomum',
            'mof_situacao_matricula' => 'cursando'
        ]);

        factory(Modulos\Academico\Models\MatriculaOfertaDisciplina::class)->create([
            'mof_mat_id' => $matricula->mat_id,
            'mof_ofd_id' => $ofertaDisciplinaCancelado->ofd_id,
            'mof_tipo_matricula' => 'matriculacomum',
            'mof_situacao_matricula' => 'cancelado'
        ]);

        factory(Modulos\Academico\Models\MatriculaOfertaDisciplina::class)->create([
            'mof_mat_id' => $matricula->mat_id,
            'mof_ofd_id' => $ofertaDisciplinaReprovado->ofd_id,
            'mof_tipo_matricula' => 'matriculacomum',
            'mof_situacao_matricula' => 'reprovado_media'
        ]);


        $rg = $this->docrepo->create(['doc_pes_id' => $matricula->aluno->pessoa->pes_id, 'doc_tpd_id' => 2, 'doc_conteudo' => '123456', 'doc_data_expedicao' => '10/10/2000']);
        $cpf = $this->docrepo->create(['doc_pes_id' => $matricula->aluno->pessoa->pes_id, 'doc_tpd_id' => 1, 'doc_conteudo' => '123456']);

        return [$matriculaOfertaDisciplina, $moduloMatriz, $ofertaDisciplina, $matricula, $moduloDisciplina];
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
