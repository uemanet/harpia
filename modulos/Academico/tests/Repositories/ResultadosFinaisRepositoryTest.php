<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use Modulos\Academico\Models\Turma;
use Modulos\Academico\Repositories\ResultadosFinaisRepository;
use Tests\ModulosTestCase;

class ResultadosFinaisRepositoryTest extends ModulosTestCase
{
    use DatabaseTransactions,
        WithoutMiddleware;

    protected $repo;

    public function setUp()
    {
        parent::setUp();
        $this->repo = $this->app->make(ResultadosFinaisRepository::class);
    }

    public function testGetResultadosFinais()
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

        $polos = factory(Modulos\Academico\Models\Polo::class, 2)->create();
        $polo = $polos->random();
        $oferta->polos()->attach($polos->pluck('pol_id')->toArray());

        $grupo = factory(Modulos\Academico\Models\Grupo::class)->create([
            'grp_trm_id' => $turma->trm_id,
            'grp_pol_id' => $polo->pol_id
        ]);

        $modulosMatriz = factory(Modulos\Academico\Models\ModuloMatriz::class, 2)->create([
            'mdo_mtc_id' => $matrizCurricular->mtc_id
        ]);

        $disciplinas = factory(Modulos\Academico\Models\Disciplina::class, 6)->create([
            'dis_nvc_id' => $curso->crs_nvc_id
        ]);

        $modulosDisciplina = new \Illuminate\Support\Collection();
        foreach ($modulosMatriz as $key => $moduloMatriz) {
            for ($i = $key * 3; $i < 3 * ($key + 1); $i++) {
                $modulosDisciplina[] = factory(Modulos\Academico\Models\ModuloDisciplina::class)->create([
                    'mdc_dis_id' => $disciplinas[$i]->dis_id,
                    'mdc_mdo_id' => $moduloMatriz->mdo_id,
                    'mdc_tipo_disciplina' => 'eletiva'
                ]);
            }
        }

        $professor = factory(Modulos\Academico\Models\Professor::class)->create();

        $tipoAvaliacao = ['numerica', 'conceitual'];

        $ofertasDeDisciplina = new \Illuminate\Support\Collection();
        foreach ($modulosDisciplina as $moduloDisciplina) {
            $ofertasDeDisciplina[] = factory(Modulos\Academico\Models\OfertaDisciplina::class)->create([
                'ofd_mdc_id' => $moduloDisciplina->mdc_id,
                'ofd_trm_id' => $turma->trm_id,
                'ofd_per_id' => $turma->trm_per_id,
                'ofd_prf_id' => $professor->prf_id,
                'ofd_tipo_avaliacao' => $tipoAvaliacao[random_int(0, 1)],
                'ofd_qtd_vagas' => 500
            ]);
        }

        factory(\Modulos\Geral\Models\Titulacao::class, 7)->create();

        $titulacaoProfessor = factory(Modulos\Geral\Models\TitulacaoInformacao::class)->create([
            'tin_pes_id' => $professor->pessoa->pes_id,
            'tin_tit_id' => random_int(2, 7),
        ]);

        $matriculaConcluida = factory(\Modulos\Academico\Models\Matricula::class)->create([
            'mat_trm_id' => $turma->trm_id,
            'mat_pol_id' => $polo->pol_id,
            'mat_grp_id' => $grupo->grp_id,
            'mat_situacao' => 'concluido',
        ]);

        $matriculaReprovada = factory(\Modulos\Academico\Models\Matricula::class)->create([
            'mat_trm_id' => $turma->trm_id,
            'mat_pol_id' => $polo->pol_id,
            'mat_grp_id' => $grupo->grp_id,
            'mat_situacao' => 'reprovado',
        ]);

        $matriculasOfertaDisciplinaAprovada = new \Illuminate\Support\Collection();
        $matriculaOfertaDisciplinaReprovada = new \Illuminate\Support\Collection();
        foreach ($ofertasDeDisciplina as $ofertaDisciplina) {
            $matriculasOfertaDisciplinaAprovada[] = factory(Modulos\Academico\Models\MatriculaOfertaDisciplina::class)->create([
                'mof_mat_id' => $matriculaConcluida->mat_id,
                'mof_ofd_id' => $ofertaDisciplina->ofd_id,
                'mof_tipo_matricula' => 'matriculacomum',
                'mof_situacao_matricula' => 'aprovado_media',
                'mof_nota1' => random_int(0, 10),
                'mof_nota2' => random_int(0, 10),
                'mof_nota3' => random_int(0, 10),
                'mof_final' => random_int(0, 10),
                'mof_recuperacao' => random_int(0, 10),
                'mof_mediafinal' => random_int(0, 10),
                'mof_conceito' => 'Bom'
            ]);

            $matriculaOfertaDisciplinaReprovada[] = factory(\Modulos\Academico\Models\MatriculaOfertaDisciplina::class)->create([
                'mof_mat_id' => $matriculaReprovada->mat_id,
                'mof_ofd_id' => $ofertaDisciplina->ofd_id,
                'mof_tipo_matricula' => 'matriculacomum',
                'mof_situacao_matricula' => 'reprovado_media',
                'mof_nota1' => random_int(0, 10),
                'mof_nota2' => random_int(0, 10),
                'mof_nota3' => random_int(0, 10),
                'mof_final' => random_int(0, 10),
                'mof_recuperacao' => random_int(0, 10),
                'mof_mediafinal' => random_int(0, 10),
                'mof_conceito' => 'Ruim'
            ]);
        }

        // Alunos concluidos
        $result = $this->repo->getResultadosFinais($turma, $polo, 'concluido');

        $this->assertTrue(is_array($result));

        // Checa estrutura
        $aluno = $matriculaConcluida->aluno->pessoa;
        $moduloMatriz = $modulosMatriz->random();
        $disciplina = $moduloMatriz->disciplinas->random(); // Checar se existe disciplina do modulo

        $this->assertArrayHasKey($polo->pol_nome, $result);

        // Filtro por polo
        $this->assertArrayHasKey($aluno->pes_nome, $result[$polo->pol_nome]);
        $this->assertEquals(1, count($result[$polo->pol_nome]));
        $this->assertArrayHasKey($moduloMatriz->mdo_nome, $result[$polo->pol_nome][$aluno->pes_nome]);
        $this->assertArrayHasKey($disciplina->dis_nome, $result[$polo->pol_nome][$aluno->pes_nome][$moduloMatriz->mdo_nome]);

        // Verifica ordenacao de disciplinas por modulo
        $nomesDeDisciplinas = [];

        foreach ($moduloMatriz->disciplinas as $disciplina) {
            $nomesDeDisciplinas[$disciplina->dis_nome] = null;
        }

        ksort($nomesDeDisciplinas);
        $fromRepository = $result[$polo->pol_nome][$aluno->pes_nome][$moduloMatriz->mdo_nome];
        $this->assertEquals(array_keys($nomesDeDisciplinas), array_keys($fromRepository));

        // Verifica todas as medias finais
        foreach ($matriculasOfertaDisciplinaAprovada as $matriculaOfertaDisciplina) {
            $moduloMatrizMatriculaOfertaDisciplina = $matriculaOfertaDisciplina->ofertaDisciplina->moduloDisciplina->modulo;
            $disciplinaMatriculaOfertaDisciplina = $matriculaOfertaDisciplina->ofertaDisciplina->moduloDisciplina->disciplina;
            $fromRepository = $result[$polo->pol_nome][$aluno->pes_nome][$moduloMatrizMatriculaOfertaDisciplina->mdo_nome];

            if ($matriculaOfertaDisciplina->ofertaDisciplina->getOriginal('ofd_tipo_avaliacao') == 'conceitual') {
                $this->assertEquals($matriculaOfertaDisciplina->mof_conceito, $fromRepository[$disciplinaMatriculaOfertaDisciplina->dis_nome]['media']);
            }

            if ($matriculaOfertaDisciplina->ofertaDisciplina->getOriginal('ofd_tipo_avaliacao') == 'numerica') {
                $this->assertEquals($matriculaOfertaDisciplina->mof_mediafinal, $fromRepository[$disciplinaMatriculaOfertaDisciplina->dis_nome]['media']);
            }
        }

        // Alunos reprovados
        $result = $this->repo->getResultadosFinais($turma, $polo, 'reprovado');

        $this->assertTrue(is_array($result));

        // Checa estrutura
        $aluno = $matriculaReprovada->aluno->pessoa;
        $moduloMatriz = $modulosMatriz->random();
        $disciplina = $moduloMatriz->disciplinas->random(); // Checar se existe disciplina do modulo

        $this->assertArrayHasKey($polo->pol_nome, $result);

        // Filtro por polo
        $this->assertArrayHasKey($aluno->pes_nome, $result[$polo->pol_nome]);
        $this->assertEquals(1, count($result[$polo->pol_nome]));
        $this->assertArrayHasKey($moduloMatriz->mdo_nome, $result[$polo->pol_nome][$aluno->pes_nome]);
        $this->assertArrayHasKey($disciplina->dis_nome, $result[$polo->pol_nome][$aluno->pes_nome][$moduloMatriz->mdo_nome]);

        // Verifica ordenacao de disciplinas por modulo
        $nomesDeDisciplinas = [];

        foreach ($moduloMatriz->disciplinas as $disciplina) {
            $nomesDeDisciplinas[$disciplina->dis_nome] = null;
        }

        ksort($nomesDeDisciplinas);
        $fromRepository = $result[$polo->pol_nome][$aluno->pes_nome][$moduloMatriz->mdo_nome];
        $this->assertEquals(array_keys($nomesDeDisciplinas), array_keys($fromRepository));

        // Verifica todas as medias finais
        foreach ($matriculaOfertaDisciplinaReprovada as $matriculaOfertaDisciplina) {
            $moduloMatrizMatriculaOfertaDisciplina = $matriculaOfertaDisciplina->ofertaDisciplina->moduloDisciplina->modulo;
            $disciplinaMatriculaOfertaDisciplina = $matriculaOfertaDisciplina->ofertaDisciplina->moduloDisciplina->disciplina;
            $fromRepository = $result[$polo->pol_nome][$aluno->pes_nome][$moduloMatrizMatriculaOfertaDisciplina->mdo_nome];

            if ($matriculaOfertaDisciplina->ofertaDisciplina->getOriginal('ofd_tipo_avaliacao') == 'conceitual') {
                $this->assertEquals($matriculaOfertaDisciplina->mof_conceito, $fromRepository[$disciplinaMatriculaOfertaDisciplina->dis_nome]['media']);
            }

            if ($matriculaOfertaDisciplina->ofertaDisciplina->getOriginal('ofd_tipo_avaliacao') == 'numerica') {
                $this->assertEquals($matriculaOfertaDisciplina->mof_mediafinal, $fromRepository[$disciplinaMatriculaOfertaDisciplina->dis_nome]['media']);
            }
        }
    }
}
