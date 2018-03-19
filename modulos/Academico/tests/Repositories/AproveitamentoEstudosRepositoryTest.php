<?php

use Tests\ModulosTestCase;
use Tests\Helpers\Reflection;
use Stevebauman\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Geral\Repositories\DocumentoRepository;
use Modulos\Academico\Models\MatriculaOfertaDisciplina;
use Modulos\Academico\Repositories\AproveitamentoEstudosRepository;
use Modulos\Academico\Repositories\MatriculaOfertaDisciplinaRepository;

class AproveitamentoEstudosRepositoryTest extends ModulosTestCase
{
    use Reflection;
    protected $docrepo;

    public function setUp()
    {
        parent::setUp();
        $this->repo = $this->app->make(AproveitamentoEstudosRepository::class);
        $this->docrepo = $this->app->make(DocumentoRepository::class);
        $this->crsRepo = $this->app->make(CursoRepository::class);

        $this->table = 'acd_matriculas_ofertas_disciplinas';
    }

    private function mockConfigs()
    {
        $modos = [
            'substituir_menor_nota',
            'substituir_media_final'
        ];

        return [
            'media_min_aprovacao' => 7,
            'media_min_final' => 5,
            'media_min_aprovacao_final' => 5,
            'modo_recuperacao' => $modos[random_int(0, 1)],
            'conceitos_aprovacao' => json_encode(['Bom', 'Muito Bom'], JSON_UNESCAPED_UNICODE)
        ];
    }

    public function createCourse()
    {
        // Necessario estar logado por conta do Vinculo
        $user = factory(\Modulos\Seguranca\Models\Usuario::class)->create();
        $this->actingAs($user);

        $curso = factory(\Modulos\Academico\Models\Curso::class)->raw();
        $configs = $this->mockConfigs();

        $data = array_merge($curso, $configs);

        $this->assertEquals(0, \Modulos\Academico\Models\Curso::all()->count());
        $this->assertEquals(0, \Modulos\Academico\Models\ConfiguracaoCurso::all()->count());
        $return = $this->crsRepo->create($data);

        $this->assertTrue(is_array($return));
        $this->assertEquals(5, \Modulos\Academico\Models\ConfiguracaoCurso::all()->count());

        return $return;
    }


    public function testgetDisciplinesNotEnroledByStudent()
    {
        $turma = factory(\Modulos\Academico\Models\Turma::class)->create();
        $matriculas = factory(\Modulos\Academico\Models\Matricula::class, 10)->create(['mat_trm_id' => $turma->trm_id]);
        factory(\Modulos\Academico\Models\OfertaDisciplina::class)->create(['ofd_trm_id' => $turma->trm_id]);

        foreach ($matriculas as $matricula) {
            $rg = $this->docrepo->create(['doc_pes_id' => $matricula->aluno->pessoa->pes_id, 'doc_tpd_id' => 2, 'doc_conteudo' => '123456', 'doc_data_expedicao' => '10/10/2000']);
            $cpf = $this->docrepo->create(['doc_pes_id' => $matricula->aluno->pessoa->pes_id, 'doc_tpd_id' => 1, 'doc_conteudo' => '123456']);
            $polo = $matricula->mat_pol_id;
        }

        $response = $this->repo->getDisciplinesNotEnroledByStudent($matricula->aluno->alu_id, $turma->trm_id);

        $this->assertNotEmpty($response);
    }

    public function testgetCourseConfiguration()
    {
        $curso = factory(\Modulos\Academico\Models\Curso::class)->create();
        $configuracoes = $this->mockConfigs();

        foreach ($configuracoes as $key => $configuracao) {
            factory(\Modulos\Academico\Models\ConfiguracaoCurso::class)
                ->create(['cfc_crs_id' => $curso->crs_id,
                          'cfc_nome' => $key,
                          'cfc_valor' => $configuracao
                    ]);
        }

        $oferta = factory(\Modulos\Academico\Models\OfertaCurso::class)->create(['ofc_crs_id' => $curso->crs_id]);
        $turma = factory(\Modulos\Academico\Models\Turma::class)->create(['trm_ofc_id' => $oferta->ofc_id]);
        $ofertaDisciplina = factory(\Modulos\Academico\Models\OfertaDisciplina::class)->create(['ofd_trm_id' => $turma->trm_id]);

        $response = $this->repo->getCourseConfiguration($ofertaDisciplina->ofd_id);

        $this->assertNotEmpty($response);
    }


    public function testaproveitarDisciplina()
    {
        $curso = factory(\Modulos\Academico\Models\Curso::class)->create();
        $configuracoes = $this->mockConfigs();

        foreach ($configuracoes as $key => $configuracao) {
            factory(\Modulos\Academico\Models\ConfiguracaoCurso::class)
                ->create(['cfc_crs_id' => $curso->crs_id,
                    'cfc_nome' => $key,
                    'cfc_valor' => $configuracao
                ]);
        }

        $oferta = factory(\Modulos\Academico\Models\OfertaCurso::class)->create(['ofc_crs_id' => $curso->crs_id]);
        $turma = factory(\Modulos\Academico\Models\Turma::class)->create(['trm_ofc_id' => $oferta->ofc_id]);
        $ofertaDisciplina = factory(\Modulos\Academico\Models\OfertaDisciplina::class)->create(['ofd_trm_id' => $turma->trm_id]);
        $matricula = factory(\Modulos\Academico\Models\Matricula::class)->create(['mat_trm_id' => $turma->trm_id]);

        $response = $this->repo->aproveitarDisciplina($ofertaDisciplina->ofd_id, $matricula->mat_id, ['mof_observacao' => 'Teste Obsevação', 'mof_conceito' => 'Muito Bom']);

        $this->assertNotEmpty($response);
        $this->assertEquals($response['type'],'success');
    }

    public function testaproveitarDisciplinaEstudanteReprovado()
    {
        $curso = factory(\Modulos\Academico\Models\Curso::class)->create();
        $configuracoes = $this->mockConfigs();

        foreach ($configuracoes as $key => $configuracao) {
            factory(\Modulos\Academico\Models\ConfiguracaoCurso::class)
                ->create(['cfc_crs_id' => $curso->crs_id,
                    'cfc_nome' => $key,
                    'cfc_valor' => $configuracao
                ]);
        }

        $oferta = factory(\Modulos\Academico\Models\OfertaCurso::class)->create(['ofc_crs_id' => $curso->crs_id]);
        $turma = factory(\Modulos\Academico\Models\Turma::class)->create(['trm_ofc_id' => $oferta->ofc_id]);
        $ofertaDisciplina = factory(\Modulos\Academico\Models\OfertaDisciplina::class)->create(['ofd_trm_id' => $turma->trm_id]);
        $matricula = factory(\Modulos\Academico\Models\Matricula::class)->create(['mat_trm_id' => $turma->trm_id, 'mat_situacao' => 'reprovado']);

        $response = $this->repo->aproveitarDisciplina($ofertaDisciplina->ofd_id, $matricula->mat_id, ['mof_observacao' => 'Teste Obsevação', 'mof_conceito' => 'Muito Bom']);

        $this->assertNotEmpty($response);
        $this->assertEquals($response['type'],'error');
    }

    public function testaproveitarDisciplinaInexistente()
    {
        $curso = factory(\Modulos\Academico\Models\Curso::class)->create();
        $configuracoes = $this->mockConfigs();

        foreach ($configuracoes as $key => $configuracao) {
            factory(\Modulos\Academico\Models\ConfiguracaoCurso::class)
                ->create(['cfc_crs_id' => $curso->crs_id,
                    'cfc_nome' => $key,
                    'cfc_valor' => $configuracao
                ]);
        }

        $oferta = factory(\Modulos\Academico\Models\OfertaCurso::class)->create(['ofc_crs_id' => $curso->crs_id]);
        $turma = factory(\Modulos\Academico\Models\Turma::class)->create(['trm_ofc_id' => $oferta->ofc_id]);
        $matricula = factory(\Modulos\Academico\Models\Matricula::class)->create(['mat_trm_id' => $turma->trm_id]);

        $response = $this->repo->aproveitarDisciplina(1, $matricula->mat_id, ['mof_observacao' => 'Teste Obsevação', 'mof_conceito' => 'Muito Bom']);

        $this->assertNotEmpty($response);
        $this->assertEquals($response['type'],'error');
    }

}
