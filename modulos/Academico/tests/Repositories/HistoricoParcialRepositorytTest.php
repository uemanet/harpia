<?php

use Tests\ModulosTestCase;
use Modulos\Academico\Models\Matricula;
use Illuminate\Database\Eloquent\Collection;
use Modulos\Geral\Repositories\DocumentoRepository;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modulos\Academico\Repositories\HistoricoParcialRepository;

class HistoricoParcialRepositorytTest extends ModulosTestCase
{
    use DatabaseTransactions,
        WithoutMiddleware;

    protected $repo;
    protected $docrepo;

    public function setUp(): void
    {
        parent::setUp();
        $this->repo = $this->app->make(HistoricoParcialRepository::class);
        $this->docrepo = $this->app->make(DocumentoRepository::class);
    }

    public function testGetGradeCurricularByMatricula()
    {
        $matricula = $this->mockGradeCurricular();

        $rg = $this->docrepo->create(['doc_pes_id' => $matricula->aluno->pessoa->pes_id, 'doc_tpd_id' => 2, 'doc_conteudo' => '123456', 'doc_data_expedicao' => '10/10/2000']);
        $cpf = $this->docrepo->create(['doc_pes_id' => $matricula->aluno->pessoa->pes_id, 'doc_tpd_id' => 1, 'doc_conteudo' => '123456']);

        $response = $this->repo->getGradeCurricularByMatricula($matricula->mat_id);

        $this->assertNotEmpty($response, '');
    }

    public function mockGradeCurricular()
    {
        $curso = factory(Modulos\Academico\Models\Curso::class)->create([
            'crs_nvc_id' => 2,
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

        $moduloDisciplina = factory(Modulos\Academico\Models\ModuloDisciplina::class)->create([
            'mdc_dis_id' => $disciplina->dis_id,
            'mdc_mdo_id' => $moduloMatriz->mdo_id,
            'mdc_tipo_disciplina' => 'eletiva'
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

        factory(\Modulos\Geral\Models\Titulacao::class, 7)->create();

        $titulacaoProfessor = factory(Modulos\Geral\Models\TitulacaoInformacao::class)->create([
            'tin_pes_id' => $professor->pessoa->pes_id,
            'tin_tit_id' => random_int(2, 7),
        ]);

        $matricula = factory(\Modulos\Academico\Models\Matricula::class)->create([
            'mat_trm_id' => $turma->trm_id,
            'mat_pol_id' => $polo->pol_id,
            'mat_grp_id' => $grupo->grp_id,
        ]);

        $matriculaOfertaDisciplina = factory(Modulos\Academico\Models\MatriculaOfertaDisciplina::class)->create([
            'mof_mat_id' => $matricula->mat_id,
            'mof_ofd_id' => $ofertaDisciplina->ofd_id,
            'mof_tipo_matricula' => 'matriculacomum',
            'mof_situacao_matricula' => 'aprovado_media',
            'mof_nota1' => 10,
            'mof_nota2' => 10,
            'mof_nota3' => 10,
            'mof_conceito' => 'Muito Bom',
            'mof_recuperacao' => 10,
            'mof_final' => 10,
            'mof_mediafinal' => 10
        ]);

        return $matricula;
    }
}
