<?php

use Tests\ModulosTestCase;
use Modulos\Academico\Models\MatriculaOfertaDisciplina;
use Modulos\Academico\Repositories\MatriculaOfertaDisciplinaRepository;
use Modulos\Geral\Repositories\DocumentoRepository;

class MatriculaOfertaDisciplinaTest extends ModulosTestCase
{
    protected $docrepo;

    public function setUp()
    {
        parent::setUp();
        $this->repo = $this->app->make(MatriculaOfertaDisciplinaRepository::class);
        $this->docrepo = $this->app->make(DocumentoRepository::class);

        $this->table = 'acd_departamento';
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

    public function testgetAllMatriculasByAluno()
    {
        $matriculaoferta = factory(MatriculaOfertaDisciplina::class)->create();

        $response = $this->repo->getAllMatriculasByAluno($matriculaoferta->matriculaCurso->aluno->alu_id);

        $this->assertNotEmpty($response);
        $this->assertCount(1, $response);
    }

    public function testgetAllMatriculasByAlunoModuloMatriz()
    {
        $data = $this->mock();

        $matriculaoferta = $data[0];
        $modulomatriz = $data[1];

        $response = $this->repo->getAllMatriculasByAlunoModuloMatriz($matriculaoferta->matriculaCurso->aluno->alu_id, $modulomatriz->mdo_id);

        $this->assertNotEmpty($response);

    }

    public function testgetMatriculasOfertasDisciplinasByMatricula()
    {
        $data = $this->mock();

        $matriculaoferta = $data[0];

        $response = $this->repo->getMatriculasOfertasDisciplinasByMatricula($matriculaoferta->matriculaCurso->mat_id, []);

        $this->assertNotEmpty($response);

        $this->assertEquals($response[0]->mof_mat_id, $matriculaoferta->mof_mat_id);
    }

    public function testgetDisciplinasCursadasByAluno()
    {
        $data = $this->mock();

        $matriculaoferta = $data[0];

        $response = $this->repo->getDisciplinasCursadasByAluno($matriculaoferta->matriculaCurso->aluno->alu_id, [
            'ofd_per_id' => $matriculaoferta->ofertaDisciplina->ofd_per_id,
            'ofd_trm_id' => $matriculaoferta->ofertaDisciplina->ofd_trm_id
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

    public function testgetDisciplinasOfertadasNotCursadasByAluno()
    {
        $data = $this->mock();

        $matriculaoferta = $data[0];

        $response = $this->repo->getDisciplinasOfertadasNotCursadasByAluno($matriculaoferta->matriculaCurso->mat_alu_id, $matriculaoferta->matriculaCurso->mat_trm_id, $matriculaoferta->ofertaDisciplina->ofd_per_id);


        $this->assertNotEmpty($response);
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

        return [$matriculaOfertaDisciplina, $moduloMatriz];
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
