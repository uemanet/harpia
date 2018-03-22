<?php
declare(strict_types=1);

use GuzzleHttp\Client;
use Tests\ModulosTestCase;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;
use Tests\Helpers\Reflection;
use GuzzleHttp\Psr7\Response;
use Harpia\Moodle\Facades\Moodle;
use GuzzleHttp\Handler\MockHandler;
use Illuminate\Support\Facades\Schema;
use Modulos\Integracao\Models\MapeamentoNota;
use Stevebauman\EloquentTable\TableCollection;
use Modulos\Academico\Models\OfertaDisciplina;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Integracao\Repositories\MapeamentoNotasRepository;

class MapeamentoNotasRepositoryTest extends ModulosTestCase
{
    use Reflection;

    protected $configuracoesCurso = [
        "media_min_aprovacao" => "7.0",
        "media_min_final" => "5.0",
        "media_min_aprovacao_final" => "5.0",
        "modo_recuperacao" => "substituir_media_final",
        "conceitos_aprovacao" => '["Bom","Muito Bom","Excelente"]',
    ];

    public function setUp()
    {
        parent::setUp();
        $this->repo = $this->app->make(MapeamentoNotasRepository::class);
        $this->table = 'int_mapeamento_itens_nota';
    }

    private function mockMapeamentoItensNotaNumerica(int $ofertaId)
    {
        return [
            'min_ofd_id' => $ofertaId,
            'min_id_nota1' => random_int(1, 2550),
            'min_id_nota2' => random_int(1, 2550),
            'min_id_nota3' => random_int(1, 2550),
            'min_id_recuperacao' => random_int(1, 2550),
            'min_id_final' => random_int(1, 2550)
        ];
    }

    private function mockMapeamentoItensNotaConceitual(int $ofertaId)
    {
        return [
            'min_ofd_id' => $ofertaId,
            'min_id_conceito' => random_int(1, 2550)
        ];
    }

    private function mockConfiguracoesCurso($modoSubstituicao = 'substituir_menor_nota')
    {
        return [
            "media_min_aprovacao" => "7.0",
            "media_min_final" => "5.0",
            "media_min_aprovacao_final" => "5.0",
            "modo_recuperacao" => $modoSubstituicao,
            "conceitos_aprovacao" => '["Bom","Muito Bom","Excelente"]',
        ];
    }

    private function mockGradeCurricularTurma()
    {
        // Curso
        $curso = factory(\Modulos\Academico\Models\Curso::class)->create();

        // Matriz curricular e Modulos Matriz
        $matrizCurricular = factory(\Modulos\Academico\Models\MatrizCurricular::class)->create([
            'mtc_crs_id' => $curso->crs_id,
        ]);

        $modulosDisciplinas = [];

        // 3 modulos
        for ($i = 0; $i <= 3; $i++) {
            $moduloMatriz = factory(\Modulos\Academico\Models\ModuloMatriz::class)->create([
                'mdo_mtc_id' => $matrizCurricular->mtc_id,
            ]);

            // 3 disciplinas por modulo
            for ($j = 0; $j <= 3; $j++) {
                $modulosDisciplinas[] = factory(\Modulos\Academico\Models\ModuloDisciplina::class)->create([
                    'mdc_mdo_id' => $moduloMatriz->mdo_id
                ]);
            }
        }

        $periodosLetivos = collect([]);

        // Periodos letivos
        $periodosLetivos[] = factory(\Modulos\Academico\Models\PeriodoLetivo::class)->create([
            'per_inicio' => '01/01/2016',
            'per_fim' => '01/06/2016'
        ]);

        $periodosLetivos[] = factory(\Modulos\Academico\Models\PeriodoLetivo::class)->create([
            'per_inicio' => '04/06/2016',
            'per_fim' => '11/12/2016'
        ]);

        $periodosLetivos[] = factory(\Modulos\Academico\Models\PeriodoLetivo::class)->create([
            'per_inicio' => '04/02/2017',
            'per_fim' => '01/06/2017'
        ]);

        // Turma
        $ofertaCurso = factory(\Modulos\Academico\Models\OfertaCurso::class)->create([
            'ofc_crs_id' => $curso->crs_id,
            'ofc_mtc_id' => $matrizCurricular->mtc_id
        ]);

        $turma = factory(\Modulos\Academico\Models\Turma::class)->create([
            'trm_ofc_id' => $ofertaCurso->ofc_id,
            'trm_per_id' => $periodosLetivos->first()->per_id,
        ]);

        // Ofertas Disciplinas na turma
        foreach ($periodosLetivos as $key => $periodo) {
            for ($i = $key * 3; $i < 3 * ($key + 1); $i++) {
                factory(OfertaDisciplina::class)->create([
                    'ofd_mdc_id' => $modulosDisciplinas[$i]->mdc_id,
                    'ofd_trm_id' => $turma->trm_id,
                    'ofd_per_id' => $periodo->per_id,
                ]);
            }
        }

        return [$matrizCurricular, $turma];
    }

    public function testCreate()
    {
        $data = factory(MapeamentoNota::class)->raw();
        $entry = $this->repo->create($data);

        $this->assertInstanceOf(MapeamentoNota::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->toArray());
    }

    public function testFind()
    {
        $entry = factory(MapeamentoNota::class)->create();
        $id = $entry->min_id;
        $fromRepository = $this->repo->find($id);

        $this->assertInstanceOf(MapeamentoNota::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepository->toArray());
        $this->assertEquals($entry->toArray(), $fromRepository->toArray());
    }

    public function testUpdate()
    {
        $entry = factory(MapeamentoNota::class)->create();
        $id = $entry->min_id;

        $data = $entry->toArray();

        $data['min_id_conceito'] = "conceito";

        $return = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(MapeamentoNota::class, $fromRepository);
        $this->assertEquals($data, $fromRepository->toArray());
    }

    public function testDelete()
    {
        $entry = factory(MapeamentoNota::class)->create();
        $id = $entry->min_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        $entries = factory(MapeamentoNota::class, 2)->create();

        $model = new MapeamentoNota();
        $expected = $model->pluck('min_id_conceito', 'min_id');
        $fromRepository = $this->repo->lists('min_id', 'min_id_conceito');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        $entries = factory(MapeamentoNota::class, 2)->create();

        factory(MapeamentoNota::class)->create([
            'min_id_conceito' => 'conceito'
        ]);

        $searchResult = $this->repo->search(array(['min_id_conceito', '=', 'conceito']));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(MapeamentoNota::class, 2)->create();

        $entry = factory(MapeamentoNota::class)->create([
            'min_id_conceito' => "conceito"
        ]);

        $expected = [
            'min_id' => $entry->min_id,
            'min_id_conceito' => $entry->min_id_conceito
        ];

        $searchResult = $this->repo->search(array(['min_id_conceito', '=', "conceito"]), ['min_id', 'min_id_conceito']);

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
        $this->assertEquals($expected, $searchResult->first()->toArray());
    }

    public function testAll()
    {
        // With empty database
        $collection = $this->repo->all();

        $this->assertEquals(0, $collection->count());

        // Non-empty database
        $created = factory(MapeamentoNota::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(MapeamentoNota::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new MapeamentoNota();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(MapeamentoNota::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(MapeamentoNota::class, 2)->create();

        $sort = [
            'field' => 'min_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->min_id);
    }

    public function testPaginateWithSearch()
    {
        factory(MapeamentoNota::class, 2)->create();
        factory(MapeamentoNota::class)->create([
            'min_id_conceito' => 'conceito',
        ]);

        $search = [
            [
                'field' => 'min_id_conceito',
                'type' => '=',
                'term' => 'conceito'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('conceito', $response->first()->min_id_conceito);
    }

    public function testPaginateRequest()
    {
        factory(MapeamentoNota::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'min_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }

    public function testIfAlunoAprovadoConceito()
    {
        $data = [
            'mof_conceito' => 'Bom'
        ];

        $result = $this->invokeMethod($this->repo, 'calcularMedia', [$data, $this->configuracoesCurso, 'Conceitual']);

        $this->assertEquals($result['mof_situacao_matricula'], 'aprovado_media');
    }

    public function testIfAlunoReprovadoConceito()
    {
        $data = [
            'mof_conceito' => 'Insuficiente'
        ];

        $result = $this->invokeMethod($this->repo, 'calcularMedia', [$data, $this->configuracoesCurso, 'Conceitual']);

        $this->assertEquals($result['mof_situacao_matricula'], 'reprovado_media');
    }

    public function testIfAlunoAprovadoMedia()
    {
        $data = [
            'mof_nota1' => 7.0,
            'mof_nota2' => 7.0,
            'mof_nota3' => 7.0
        ];

        $result = $this->invokeMethod($this->repo, 'calcularMedia', [$data, $this->configuracoesCurso]);

        $this->assertEquals($result['mof_mediafinal'], 7.0);
        $this->assertEquals($result['mof_situacao_matricula'], 'aprovado_media');
    }

    public function testIfAlunoReprovadoMedia()
    {
        $data = [
            'mof_nota1' => 4.0,
            'mof_nota2' => 5.0,
            'mof_nota3' => 3.0
        ];

        $result = $this->invokeMethod($this->repo, 'calcularMedia', [$data, $this->configuracoesCurso]);

        $this->assertEquals($result['mof_mediafinal'], 4.0);
        $this->assertEquals($result['mof_situacao_matricula'], 'reprovado_media');
    }

    public function testIfAlunoIsAprovadoFinalWithoutRecuperacaoSubstituirMedia()
    {
        $data = [
            'mof_nota1' => 7.0,
            'mof_nota2' => 7.0,
            'mof_nota3' => 6.0,
            'mof_final' => 6.0
        ];

        $result = $this->invokeMethod($this->repo, 'calcularMedia', [$data, $this->configuracoesCurso]);

        $this->assertEquals($result['mof_mediafinal'], 6.33);
        $this->assertEquals($result['mof_situacao_matricula'], 'aprovado_final');
    }

    public function testIfAlunoIsReprovadoFinalWithoutRecuperacaoSubstituirMedia()
    {
        $data = [
            'mof_nota1' => 7.0,
            'mof_nota2' => 7.0,
            'mof_nota3' => 6.0,
            'mof_final' => 3.0
        ];

        $result = $this->invokeMethod($this->repo, 'calcularMedia', [$data, $this->configuracoesCurso]);

        $this->assertEquals($result['mof_mediafinal'], 4.83);
        $this->assertEquals($result['mof_situacao_matricula'], 'reprovado_final');
    }

    /* Testes com Modo de Recuperação Substituir Media Final */

    public function testIfAlunoAprovadoMediaWithRecuperacaoSubstituirMedia()
    {
        $data = [
            'mof_nota1' => 7.0,
            'mof_nota2' => 7.0,
            'mof_nota3' => 6.0,
            'mof_recuperacao' => 7.0
        ];

        $result = $this->invokeMethod($this->repo, 'calcularMedia', [$data, $this->configuracoesCurso]);

        $this->assertEquals($result['mof_mediafinal'], 7.0);
        $this->assertEquals($result['mof_situacao_matricula'], 'aprovado_media');
    }

    public function testIfAlunoReprovadoMediaWithRecuperacaoSubstituirMedia()
    {
        $data = [
            'mof_nota1' => 5.0,
            'mof_nota2' => 5.0,
            'mof_nota3' => 4.0,
            'mof_recuperacao' => 4.0
        ];

        $result = $this->invokeMethod($this->repo, 'calcularMedia', [$data, $this->configuracoesCurso]);

        $this->assertEquals($result['mof_mediafinal'], 4.67);
        $this->assertEquals($result['mof_situacao_matricula'], 'reprovado_media');
    }

    public function testIfAlunoIsAprovadoFinalWithRecuperacaoSubstituirMedia()
    {
        $data = [
            'mof_nota1' => 7.0,
            'mof_nota2' => 7.0,
            'mof_nota3' => 6.0,
            'mof_recuperacao' => 5.0,
            'mof_final' => 5.0
        ];

        $result = $this->invokeMethod($this->repo, 'calcularMedia', [$data, $this->configuracoesCurso]);

        $this->assertEquals($result['mof_mediafinal'], 5.83);
        $this->assertEquals($result['mof_situacao_matricula'], 'aprovado_final');
    }

    public function testIfAlunoIsReprovadoFinalWithRecuperacaoSubstituirMedia()
    {
        $data = [
            'mof_nota1' => 7.0,
            'mof_nota2' => 7.0,
            'mof_nota3' => 6.0,
            'mof_recuperacao' => 5.0,
            'mof_final' => 3.0
        ];

        $result = $this->invokeMethod($this->repo, 'calcularMedia', [$data, $this->configuracoesCurso]);

        $this->assertEquals($result['mof_mediafinal'], 4.83);
        $this->assertEquals($result['mof_situacao_matricula'], 'reprovado_final');
    }

    /* Testes com Modo de Recuperação Substituir Menor Nota */

    public function testIfAlunoAprovadoMediaWithRecuperacaoSubstituirMenorNota()
    {
        $this->configuracoesCurso['modo_recuperacao'] = 'substituir_menor_nota';

        $data = [
            'mof_nota1' => 7.0,
            'mof_nota2' => 7.0,
            'mof_nota3' => 6.0,
            'mof_recuperacao' => 7.0
        ];

        $result = $this->invokeMethod($this->repo, 'calcularMedia', [$data, $this->configuracoesCurso]);

        $this->assertEquals($result['mof_mediafinal'], 7.0);
        $this->assertEquals($result['mof_situacao_matricula'], 'aprovado_media');
    }

    public function testIfAlunoReprovadoMediaWithRecuperacaoSubstituirMenorNota()
    {
        $this->configuracoesCurso['modo_recuperacao'] = 'substituir_menor_nota';

        $data = [
            'mof_nota1' => 5.0,
            'mof_nota2' => 5.0,
            'mof_nota3' => 4.0,
            'mof_recuperacao' => 6.0
        ];

        $result = $this->invokeMethod($this->repo, 'calcularMedia', [$data, $this->configuracoesCurso]);

        $this->assertEquals($result['mof_mediafinal'], 5.33);
        $this->assertEquals($result['mof_situacao_matricula'], 'reprovado_media');
    }

    public function testIfAlunoIsAprovadoFinalWithRecuperacaoSubstituirMenorNota()
    {
        $this->configuracoesCurso['modo_recuperacao'] = 'substituir_menor_nota';

        $data = [
            'mof_nota1' => 7.0,
            'mof_nota2' => 7.0,
            'mof_nota3' => 5.0,
            'mof_recuperacao' => 6.0,
            'mof_final' => 5.0
        ];

        $result = $this->invokeMethod($this->repo, 'calcularMedia', [$data, $this->configuracoesCurso]);

        $this->assertEquals($result['mof_mediafinal'], 5.83);
        $this->assertEquals($result['mof_situacao_matricula'], 'aprovado_final');
    }

    public function testIfAlunoIsReprovadoFinalWithRecuperacaoSubstituirMenorNota()
    {
        $this->configuracoesCurso['modo_recuperacao'] = 'substituir_menor_nota';

        $data = [
            'mof_nota1' => 7.0,
            'mof_nota2' => 7.0,
            'mof_nota3' => 5.0,
            'mof_recuperacao' => 6.0,
            'mof_final' => 3.0
        ];

        $result = $this->invokeMethod($this->repo, 'calcularMedia', [$data, $this->configuracoesCurso]);

        $this->assertEquals($result['mof_mediafinal'], 4.83);
        $this->assertEquals($result['mof_situacao_matricula'], 'reprovado_final');
    }

    public function testSetMapeamentoNotas()
    {
        // Disciplina numerica
        $ofertaDisciplina = factory(OfertaDisciplina::class)->create([
            'ofd_tipo_avaliacao' => 'numerica'
        ]);

        $this->assertEquals(0, MapeamentoNota::all()->count());

        $data = $this->mockMapeamentoItensNotaNumerica($ofertaDisciplina->ofd_id);
        $result = $this->repo->setMapeamentoNotas($data);

        $this->assertEquals(1, MapeamentoNota::all()->count());
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('msg', $result));

        // Disciplina conceito
        $ofertaDisciplina = factory(OfertaDisciplina::class)->create([
            'ofd_tipo_avaliacao' => 'conceitual'
        ]);

        $data = $this->mockMapeamentoItensNotaConceitual($ofertaDisciplina->ofd_id);
        $result = $this->repo->setMapeamentoNotas($data);

        $this->assertEquals(2, MapeamentoNota::all()->count());
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('msg', $result));
    }

    public function testSetMapeamentoNotasOfertaInexistente()
    {
        $this->assertEquals(0, MapeamentoNota::all()->count());

        $data = $this->mockMapeamentoItensNotaNumerica(random_int(1, 40));
        $result = $this->repo->setMapeamentoNotas($data);

        $this->assertEquals(0, MapeamentoNota::all()->count());
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('error', $result));
    }

    public function testSetMapeamentoNotasOfertaUpdate()
    {
        // Disciplina numerica
        $ofertaDisciplina = factory(OfertaDisciplina::class)->create([
            'ofd_tipo_avaliacao' => 'numerica'
        ]);

        $this->assertEquals(0, MapeamentoNota::all()->count());

        $data = $this->mockMapeamentoItensNotaNumerica($ofertaDisciplina->ofd_id);
        $result = $this->repo->setMapeamentoNotas($data);

        $this->assertEquals(1, MapeamentoNota::all()->count());
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('msg', $result));

        // Atualiza dados de um mapeamento ja existente
        $data = $this->mockMapeamentoItensNotaNumerica($ofertaDisciplina->ofd_id);
        $result = $this->repo->setMapeamentoNotas($data);

        $this->assertEquals(1, MapeamentoNota::all()->count());
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('msg', $result));
    }

    public function testSetMapeamentoNotasOfertaException()
    {
        // Disciplina numerica
        $ofertaDisciplina = factory(OfertaDisciplina::class)->create([
            'ofd_tipo_avaliacao' => 'numerica'
        ]);

        // Debug false
        config(['app.debug' => false]);

        Schema::table($this->table, function ($table) {
            $table->dropColumn('min_id_recuperacao');
        });

        $data = $this->mockMapeamentoItensNotaNumerica($ofertaDisciplina->ofd_id);
        $result = $this->repo->setMapeamentoNotas($data);

        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('error', $result));
    }

    public function testGetGradeCurricularByTurma()
    {
        list(, $turma) = $this->mockGradeCurricularTurma();

        $result = $this->repo->getGradeCurricularByTurma($turma->trm_id);

        $this->assertEquals(3, count($result));

        foreach ($result as $periodo) {
            $this->assertArrayHasKey('per_id', $periodo);
            $this->assertArrayHasKey('per_nome', $periodo);
            $this->assertEquals(3, $periodo['ofertas']->count());
        }
    }

    public function testMapearNotasAlunoNumerica()
    {
        // Mock oferta disciplina e mapeamento
        $ofertaDisciplina = factory(OfertaDisciplina::class)->create([
            'ofd_tipo_avaliacao' => 'numerica'
        ]);

        $matriculaOfertaDisciplina = factory(\Modulos\Academico\Models\MatriculaOfertaDisciplina::class)->create([
            'mof_ofd_id' => $ofertaDisciplina->ofd_id
        ]);

        $this->assertEquals(0, MapeamentoNota::all()->count());

        $data = $this->mockMapeamentoItensNotaNumerica($ofertaDisciplina->ofd_id);
        $this->repo->setMapeamentoNotas($data);

        $this->assertEquals(1, MapeamentoNota::all()->count());

        // Mock ambiente e integracao ambiente turma
        $moodle = [
            'amb_nome' => 'Moodle',
            'amb_versao' => '3.2+',
            'amb_url' => "http://localhost:8080"
        ];

        $ambienteVirtual = factory(Modulos\Integracao\Models\AmbienteVirtual::class)->create($moodle);

        // Integracao
        $servico = factory(Modulos\Integracao\Models\Servico::class)->create([
            'ser_id' => 2,
            'ser_nome' => "Integração",
            'ser_slug' => "local_integracao"
        ]);

        factory(\Modulos\Integracao\Models\AmbienteServico::class)->create([
            'asr_amb_id' => $ambienteVirtual->amb_id,
            'asr_ser_id' => $servico->ser_id,
            'asr_token' => "aksjhdeuig2768125sahsjhdvjahsy"
        ]);

        // Turma
        $turma = \Modulos\Academico\Models\Turma::find($ofertaDisciplina->ofd_trm_id);
        factory(\Modulos\Integracao\Models\AmbienteTurma::class)->create([
            'atr_trm_id' => $turma->trm_id,
            'atr_amb_id' => $ambienteVirtual->amb_id
        ]);

        // Mock do servidor
        $container = [];
        $history = Middleware::history($container);

        // Mock de respostas do servidor
        $mock = new MockHandler([
            new Response(200, ['content-type' => 'application/text'], json_encode([
                "pes_id" => random_int(1, 10),
                "grades" => json_encode([
                    [
                        "id" => random_int(300, 1000),
                        "tipo" => "nota1",
                        "nota" => random_int(0, 10)
                    ],
                    [
                        "id" => random_int(300, 1000),
                        "tipo" => "nota2",
                        "nota" => random_int(0, 10)
                    ],
                    [
                        "id" => random_int(300, 1000),
                        "tipo" => "nota3",
                        "nota" => random_int(0, 10)
                    ],
                    [
                        "id" => random_int(300, 1000),
                        "tipo" => "recuperacao",
                        "nota" => random_int(0, 10)
                    ],
                    [
                        "id" => random_int(300, 1000),
                        "tipo" => "final",
                        "nota" => random_int(0, 10)
                    ]
                ]),
                "status" => "success",
                "message" => "Notas mapeadas com sucesso"
            ])),
        ]);

        $handler = HandlerStack::create($mock);
        $handler->push($history);
        $client = new Client(['handler' => $handler]);

        // Seta cliente de testes
        Moodle::setClient($client);

        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota1));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota2));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota3));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_recuperacao));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_final));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_mediafinal));

        $result = $this->repo->mapearNotasAluno($ofertaDisciplina, $matriculaOfertaDisciplina, $this->configuracoesCurso);
        $this->assertEquals('success', $result['status']);

        // Atualiza a partir do DB
        $matriculaOfertaDisciplina = \Modulos\Academico\Models\MatriculaOfertaDisciplina::find($matriculaOfertaDisciplina->mof_id);

        $this->assertFalse(is_null($matriculaOfertaDisciplina->mof_nota1));
        $this->assertFalse(is_null($matriculaOfertaDisciplina->mof_nota2));
        $this->assertFalse(is_null($matriculaOfertaDisciplina->mof_nota3));
        $this->assertFalse(is_null($matriculaOfertaDisciplina->mof_recuperacao));
        $this->assertFalse(is_null($matriculaOfertaDisciplina->mof_final));
        $this->assertFalse(is_null($matriculaOfertaDisciplina->mof_mediafinal));
    }

    public function testMapearNotasAlunoConceitual()
    {
        // Mock oferta disciplina e mapeamento
        $ofertaDisciplina = factory(OfertaDisciplina::class)->create([
            'ofd_tipo_avaliacao' => 'conceitual'
        ]);

        $matriculaOfertaDisciplina = factory(\Modulos\Academico\Models\MatriculaOfertaDisciplina::class)->create([
            'mof_ofd_id' => $ofertaDisciplina->ofd_id
        ]);

        $this->assertEquals(0, MapeamentoNota::all()->count());

        $data = $this->mockMapeamentoItensNotaConceitual($ofertaDisciplina->ofd_id);
        $this->repo->setMapeamentoNotas($data);

        $this->assertEquals(1, MapeamentoNota::all()->count());

        // Mock ambiente e integracao ambiente turma
        $moodle = [
            'amb_nome' => 'Moodle',
            'amb_versao' => '3.2+',
            'amb_url' => "http://localhost:8080"
        ];

        $ambienteVirtual = factory(Modulos\Integracao\Models\AmbienteVirtual::class)->create($moodle);

        // Integracao
        $servico = factory(Modulos\Integracao\Models\Servico::class)->create([
            'ser_id' => 2,
            'ser_nome' => "Integração",
            'ser_slug' => "local_integracao"
        ]);

        factory(\Modulos\Integracao\Models\AmbienteServico::class)->create([
            'asr_amb_id' => $ambienteVirtual->amb_id,
            'asr_ser_id' => $servico->ser_id,
            'asr_token' => "aksjhdeuig2768125sahsjhdvjahsy"
        ]);

        // Turma
        $turma = \Modulos\Academico\Models\Turma::find($ofertaDisciplina->ofd_trm_id);
        factory(\Modulos\Integracao\Models\AmbienteTurma::class)->create([
            'atr_trm_id' => $turma->trm_id,
            'atr_amb_id' => $ambienteVirtual->amb_id
        ]);

        // Mock do servidor
        $container = [];
        $history = Middleware::history($container);

        $conceitos = [
            "Bom", "Muito Bom", "Excelente"
        ];

        // Mock de respostas do servidor
        $mock = new MockHandler([
            new Response(200, ['content-type' => 'application/text'], json_encode([
                "pes_id" => random_int(1, 10),
                "grades" => json_encode([
                    [
                        "id" => random_int(300, 1000),
                        "tipo" => "conceito",
                        "nota" => $conceitos[random_int(0, 2)]
                    ],
                ]),
                "status" => "success",
                "message" => "Notas mapeadas com sucesso"
            ])),
        ]);

        $handler = HandlerStack::create($mock);
        $handler->push($history);
        $client = new Client(['handler' => $handler]);

        // Seta cliente de testes
        Moodle::setClient($client);

        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota1));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota2));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota3));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_recuperacao));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_conceito));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_final));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_mediafinal));

        $result = $this->repo->mapearNotasAluno($ofertaDisciplina, $matriculaOfertaDisciplina, $this->configuracoesCurso);
        $this->assertEquals('success', $result['status']);

        // Atualiza a partir do DB
        $matriculaOfertaDisciplina = \Modulos\Academico\Models\MatriculaOfertaDisciplina::find($matriculaOfertaDisciplina->mof_id);

        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota1));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota2));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota3));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_recuperacao));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_final));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_mediafinal));

        $this->assertFalse(is_null($matriculaOfertaDisciplina->mof_conceito)); // Disciplina conceitual
    }

    public function testMapearNotasAlunoWithoutMapeamento()
    {
        // Mock oferta disciplina e mapeamento
        $ofertaDisciplina = factory(OfertaDisciplina::class)->create([
            'ofd_tipo_avaliacao' => 'numerica'
        ]);

        $matriculaOfertaDisciplina = factory(\Modulos\Academico\Models\MatriculaOfertaDisciplina::class)->create([
            'mof_ofd_id' => $ofertaDisciplina->ofd_id
        ]);

        $this->assertEquals(0, MapeamentoNota::all()->count());

        // Nao mapeia os ids de itens de nota - Evitado para teste

        // Mock ambiente e integracao ambiente turma
        $moodle = [
            'amb_nome' => 'Moodle',
            'amb_versao' => '3.2+',
            'amb_url' => "http://localhost:8080"
        ];

        $ambienteVirtual = factory(Modulos\Integracao\Models\AmbienteVirtual::class)->create($moodle);

        // Integracao
        $servico = factory(Modulos\Integracao\Models\Servico::class)->create([
            'ser_id' => 2,
            'ser_nome' => "Integração",
            'ser_slug' => "local_integracao"
        ]);

        factory(\Modulos\Integracao\Models\AmbienteServico::class)->create([
            'asr_amb_id' => $ambienteVirtual->amb_id,
            'asr_ser_id' => $servico->ser_id,
            'asr_token' => "aksjhdeuig2768125sahsjhdvjahsy"
        ]);

        // Turma
        $turma = \Modulos\Academico\Models\Turma::find($ofertaDisciplina->ofd_trm_id);
        factory(\Modulos\Integracao\Models\AmbienteTurma::class)->create([
            'atr_trm_id' => $turma->trm_id,
            'atr_amb_id' => $ambienteVirtual->amb_id
        ]);

        // Mock do servidor
        $container = [];
        $history = Middleware::history($container);

        // Mock de respostas do servidor
        $mock = new MockHandler([
            new Response(200, ['content-type' => 'application/text'], json_encode([
                "pes_id" => random_int(1, 10),
                "grades" => json_encode([
                    [
                        "id" => random_int(300, 1000),
                        "tipo" => "nota1",
                        "nota" => random_int(0, 10)
                    ],
                    [
                        "id" => random_int(300, 1000),
                        "tipo" => "nota2",
                        "nota" => random_int(0, 10)
                    ],
                    [
                        "id" => random_int(300, 1000),
                        "tipo" => "nota3",
                        "nota" => random_int(0, 10)
                    ],
                    [
                        "id" => random_int(300, 1000),
                        "tipo" => "recuperacao",
                        "nota" => random_int(0, 10)
                    ],
                    [
                        "id" => random_int(300, 1000),
                        "tipo" => "final",
                        "nota" => random_int(0, 10)
                    ]
                ]),
                "status" => "success",
                "message" => "Notas mapeadas com sucesso"
            ])),
        ]);

        $handler = HandlerStack::create($mock);
        $handler->push($history);
        $client = new Client(['handler' => $handler]);

        // Seta cliente de testes
        Moodle::setClient($client);

        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota1));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota2));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota3));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_recuperacao));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_final));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_mediafinal));

        $result = $this->repo->mapearNotasAluno($ofertaDisciplina, $matriculaOfertaDisciplina, $this->configuracoesCurso);
        $this->assertEquals('error', $result['status']);

        // Atualiza a partir do DB
        $matriculaOfertaDisciplina = \Modulos\Academico\Models\MatriculaOfertaDisciplina::find($matriculaOfertaDisciplina->mof_id);

        // Nao deve alterar as notas, ja que nao ha mapeamento para tal
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota1));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota2));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota3));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_recuperacao));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_final));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_mediafinal));
    }

    public function testMapearNotasAlunoWithoutAmbiente()
    {
        // Mock oferta disciplina e mapeamento
        $ofertaDisciplina = factory(OfertaDisciplina::class)->create([
            'ofd_tipo_avaliacao' => 'numerica'
        ]);

        $matriculaOfertaDisciplina = factory(\Modulos\Academico\Models\MatriculaOfertaDisciplina::class)->create([
            'mof_ofd_id' => $ofertaDisciplina->ofd_id
        ]);

        $this->assertEquals(0, MapeamentoNota::all()->count());

        $data = $this->mockMapeamentoItensNotaNumerica($ofertaDisciplina->ofd_id);
        $this->repo->setMapeamentoNotas($data);

        $this->assertEquals(1, MapeamentoNota::all()->count());

        // Turma
        $turma = \Modulos\Academico\Models\Turma::find($ofertaDisciplina->ofd_trm_id);
        factory(\Modulos\Integracao\Models\AmbienteTurma::class)->create([
            'atr_trm_id' => $turma->trm_id,
            'atr_amb_id' => random_int(1, 10)
        ]);

        // Mock do servidor
        $container = [];
        $history = Middleware::history($container);

        // Mock de respostas do servidor
        $mock = new MockHandler([
            new Response(200, ['content-type' => 'application/text'], json_encode([
                "pes_id" => random_int(1, 10),
                "grades" => json_encode([]),
                "status" => "success",
                "message" => "Notas mapeadas com sucesso"
            ])),
        ]);

        $handler = HandlerStack::create($mock);
        $handler->push($history);
        $client = new Client(['handler' => $handler]);

        // Seta cliente de testes
        Moodle::setClient($client);

        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota1));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota2));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota3));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_recuperacao));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_final));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_mediafinal));

        $result = $this->repo->mapearNotasAluno($ofertaDisciplina, $matriculaOfertaDisciplina, $this->configuracoesCurso);
        $this->assertEquals('error', $result['status']);

        // Atualiza a partir do DB
        $matriculaOfertaDisciplina = \Modulos\Academico\Models\MatriculaOfertaDisciplina::find($matriculaOfertaDisciplina->mof_id);

        // Notas nao devem mudar, ja que nao ha ambiente para se comunicar
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota1));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota2));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota3));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_recuperacao));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_final));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_mediafinal));
    }

    public function testMapearNotasAlunoWithoutIntegracao()
    {
        // Mock oferta disciplina e mapeamento
        $ofertaDisciplina = factory(OfertaDisciplina::class)->create([
            'ofd_tipo_avaliacao' => 'numerica'
        ]);

        $matriculaOfertaDisciplina = factory(\Modulos\Academico\Models\MatriculaOfertaDisciplina::class)->create([
            'mof_ofd_id' => $ofertaDisciplina->ofd_id
        ]);

        $this->assertEquals(0, MapeamentoNota::all()->count());

        $data = $this->mockMapeamentoItensNotaNumerica($ofertaDisciplina->ofd_id);
        $this->repo->setMapeamentoNotas($data);

        $this->assertEquals(1, MapeamentoNota::all()->count());

        // Mock ambiente e integracao ambiente turma
        $moodle = [
            'amb_nome' => 'Moodle',
            'amb_versao' => '3.2+',
            'amb_url' => "http://localhost:8080"
        ];

        $ambienteVirtual = factory(Modulos\Integracao\Models\AmbienteVirtual::class)->create($moodle);

        // Integracao - Evitado para teste

        // Turma
        $turma = \Modulos\Academico\Models\Turma::find($ofertaDisciplina->ofd_trm_id);
        factory(\Modulos\Integracao\Models\AmbienteTurma::class)->create([
            'atr_trm_id' => $turma->trm_id,
            'atr_amb_id' => $ambienteVirtual->amb_id
        ]);

        // Mock do servidor
        $container = [];
        $history = Middleware::history($container);

        // Mock de respostas do servidor
        $mock = new MockHandler([
            new Response(200, ['content-type' => 'application/text'], json_encode([
                "pes_id" => random_int(1, 10),
                "grades" => json_encode([]),
                "status" => "success",
                "message" => "Notas mapeadas com sucesso"
            ])),
        ]);

        $handler = HandlerStack::create($mock);
        $handler->push($history);
        $client = new Client(['handler' => $handler]);

        // Seta cliente de testes
        Moodle::setClient($client);

        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota1));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota2));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota3));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_recuperacao));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_final));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_mediafinal));

        $result = $this->repo->mapearNotasAluno($ofertaDisciplina, $matriculaOfertaDisciplina, $this->configuracoesCurso);
        $this->assertEquals('error', $result['status']);

        // Atualiza a partir do DB
        $matriculaOfertaDisciplina = \Modulos\Academico\Models\MatriculaOfertaDisciplina::find($matriculaOfertaDisciplina->mof_id);

        // Nao deve mudar as notas, ja que nao ha servico de integracao configurado
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota1));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota2));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota3));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_recuperacao));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_final));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_mediafinal));
    }

    public function testAproveitamentoEstudosNumerica()
    {
        // Mock oferta disciplina e mapeamento
        $ofertaDisciplina = factory(OfertaDisciplina::class)->create([
            'ofd_tipo_avaliacao' => 'numerica'
        ]);

        $matriculaOfertaDisciplina = factory(\Modulos\Academico\Models\MatriculaOfertaDisciplina::class)->create([
            'mof_ofd_id' => $ofertaDisciplina->ofd_id,
            'mof_tipo_matricula' => "aproveitamento"
        ]);

        $this->assertEquals(0, MapeamentoNota::all()->count());

        $data = [
            "min_ofd_id" => $ofertaDisciplina->ofd_id,
            "min_id_aproveitamento" => random_int(1, 1000)
        ];

        $this->repo->setMapeamentoNotas($data);

        $this->assertEquals(1, MapeamentoNota::all()->count());

        // Mock ambiente e integracao ambiente turma
        $moodle = [
            'amb_nome' => 'Moodle',
            'amb_versao' => '3.2+',
            'amb_url' => "http://localhost:8080"
        ];

        $ambienteVirtual = factory(Modulos\Integracao\Models\AmbienteVirtual::class)->create($moodle);

        // Integracao
        $servico = factory(Modulos\Integracao\Models\Servico::class)->create([
            'ser_id' => 2,
            'ser_nome' => "Integração",
            'ser_slug' => "local_integracao"
        ]);

        factory(\Modulos\Integracao\Models\AmbienteServico::class)->create([
            'asr_amb_id' => $ambienteVirtual->amb_id,
            'asr_ser_id' => $servico->ser_id,
            'asr_token' => "aksjhdeuig2768125sahsjhdvjahsy"
        ]);

        // Turma
        $turma = \Modulos\Academico\Models\Turma::find($ofertaDisciplina->ofd_trm_id);
        factory(\Modulos\Integracao\Models\AmbienteTurma::class)->create([
            'atr_trm_id' => $turma->trm_id,
            'atr_amb_id' => $ambienteVirtual->amb_id
        ]);

        // Mock do servidor
        $container = [];
        $history = Middleware::history($container);

        // Mock de respostas do servidor
        $mock = new MockHandler([
            new Response(200, ['content-type' => 'application/text'], json_encode([
                "pes_id" => random_int(1, 10),
                "grades" => json_encode([
                    [
                        "id" => random_int(300, 1000),
                        "tipo" => "aproveitamento",
                        "nota" => random_int(0, 10)
                    ],
                ]),
                "status" => "success",
                "message" => "Notas mapeadas com sucesso"
            ])),
        ]);

        $handler = HandlerStack::create($mock);
        $handler->push($history);
        $client = new Client(['handler' => $handler]);

        // Seta cliente de testes
        Moodle::setClient($client);

        $statusMatricula = $matriculaOfertaDisciplina->mof_situacao_matricula;

        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota1));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota2));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota3));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_recuperacao));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_final));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_mediafinal));

        $result = $this->repo->mapearNotasAluno($ofertaDisciplina, $matriculaOfertaDisciplina, $this->configuracoesCurso);
        $this->assertEquals('success', $result['status']);

        // Atualiza a partir do DB
        $matriculaOfertaDisciplina = \Modulos\Academico\Models\MatriculaOfertaDisciplina::find($matriculaOfertaDisciplina->mof_id);

        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota1));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota2));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota3));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_recuperacao));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_final));
        $this->assertFalse(is_null($matriculaOfertaDisciplina->mof_mediafinal));

        // Status deve mudar por conta do aproveitamento
        $this->assertFalse($statusMatricula == $matriculaOfertaDisciplina->mof_situacao_matricula);
    }

    public function testAproveitamentoEstudosConceito()
    {
        // Mock oferta disciplina e mapeamento
        $ofertaDisciplina = factory(OfertaDisciplina::class)->create([
            'ofd_tipo_avaliacao' => 'conceitual'
        ]);

        $matriculaOfertaDisciplina = factory(\Modulos\Academico\Models\MatriculaOfertaDisciplina::class)->create([
            'mof_ofd_id' => $ofertaDisciplina->ofd_id,
            'mof_tipo_matricula' => "aproveitamento"
        ]);

        $this->assertEquals(0, MapeamentoNota::all()->count());

        $data = [
            "min_ofd_id" => $ofertaDisciplina->ofd_id,
            "min_id_aproveitamento" => random_int(1, 1000)
        ];

        $this->repo->setMapeamentoNotas($data);

        $this->assertEquals(1, MapeamentoNota::all()->count());

        // Mock ambiente e integracao ambiente turma
        $moodle = [
            'amb_nome' => 'Moodle',
            'amb_versao' => '3.2+',
            'amb_url' => "http://localhost:8080"
        ];

        $ambienteVirtual = factory(Modulos\Integracao\Models\AmbienteVirtual::class)->create($moodle);

        // Integracao
        $servico = factory(Modulos\Integracao\Models\Servico::class)->create([
            'ser_id' => 2,
            'ser_nome' => "Integração",
            'ser_slug' => "local_integracao"
        ]);

        factory(\Modulos\Integracao\Models\AmbienteServico::class)->create([
            'asr_amb_id' => $ambienteVirtual->amb_id,
            'asr_ser_id' => $servico->ser_id,
            'asr_token' => "aksjhdeuig2768125sahsjhdvjahsy"
        ]);

        // Turma
        $turma = \Modulos\Academico\Models\Turma::find($ofertaDisciplina->ofd_trm_id);
        factory(\Modulos\Integracao\Models\AmbienteTurma::class)->create([
            'atr_trm_id' => $turma->trm_id,
            'atr_amb_id' => $ambienteVirtual->amb_id
        ]);

        // Mock do servidor
        $container = [];
        $history = Middleware::history($container);

        // Mock de respostas do servidor
        $mock = new MockHandler([
            new Response(200, ['content-type' => 'application/text'], json_encode([
                "pes_id" => random_int(1, 10),
                "grades" => json_encode([
                    [
                        "id" => random_int(300, 1000),
                        "tipo" => "aproveitamento",
                        "nota" => "Excelente"
                    ],
                ]),
                "status" => "success",
                "message" => "Notas mapeadas com sucesso"
            ])),
        ]);

        $handler = HandlerStack::create($mock);
        $handler->push($history);
        $client = new Client(['handler' => $handler]);

        // Seta cliente de testes
        Moodle::setClient($client);

        $statusMatricula = $matriculaOfertaDisciplina->mof_situacao_matricula;

        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota1));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota2));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota3));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_recuperacao));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_final));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_conceito));

        $result = $this->repo->mapearNotasAluno($ofertaDisciplina, $matriculaOfertaDisciplina, $this->configuracoesCurso);
        $this->assertEquals('success', $result['status']);

        // Atualiza a partir do DB
        $matriculaOfertaDisciplina = \Modulos\Academico\Models\MatriculaOfertaDisciplina::find($matriculaOfertaDisciplina->mof_id);

        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota1));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota2));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota3));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_recuperacao));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_final));
        $this->assertFalse(is_null($matriculaOfertaDisciplina->mof_conceito));

        // Status deve mudar por conta do aproveitamento
        $this->assertFalse($statusMatricula == $matriculaOfertaDisciplina->mof_situacao_matricula);
    }

    public function testAproveitamentoEstudosWithoutMapeamento()
    {
        // Mock oferta disciplina e mapeamento
        $ofertaDisciplina = factory(OfertaDisciplina::class)->create([
            'ofd_tipo_avaliacao' => 'conceitual'
        ]);

        $matriculaOfertaDisciplina = factory(\Modulos\Academico\Models\MatriculaOfertaDisciplina::class)->create([
            'mof_ofd_id' => $ofertaDisciplina->ofd_id,
            'mof_tipo_matricula' => "aproveitamento"
        ]);


        // Sem mapeamento - teste
        $this->assertEquals(0, MapeamentoNota::all()->count());

        // Mock ambiente e integracao ambiente turma
        $moodle = [
            'amb_nome' => 'Moodle',
            'amb_versao' => '3.2+',
            'amb_url' => "http://localhost:8080"
        ];

        $ambienteVirtual = factory(Modulos\Integracao\Models\AmbienteVirtual::class)->create($moodle);

        // Integracao
        $servico = factory(Modulos\Integracao\Models\Servico::class)->create([
            'ser_id' => 2,
            'ser_nome' => "Integração",
            'ser_slug' => "local_integracao"
        ]);

        factory(\Modulos\Integracao\Models\AmbienteServico::class)->create([
            'asr_amb_id' => $ambienteVirtual->amb_id,
            'asr_ser_id' => $servico->ser_id,
            'asr_token' => "aksjhdeuig2768125sahsjhdvjahsy"
        ]);

        // Turma
        $turma = \Modulos\Academico\Models\Turma::find($ofertaDisciplina->ofd_trm_id);
        factory(\Modulos\Integracao\Models\AmbienteTurma::class)->create([
            'atr_trm_id' => $turma->trm_id,
            'atr_amb_id' => $ambienteVirtual->amb_id
        ]);

        // Mock do servidor
        $container = [];
        $history = Middleware::history($container);

        // Mock de respostas do servidor
        $mock = new MockHandler([
            new Response(200, ['content-type' => 'application/text'], json_encode([
                "pes_id" => random_int(1, 10),
                "grades" => json_encode([
                    [
                        "id" => random_int(300, 1000),
                        "tipo" => "aproveitamento",
                        "nota" => "Excelente"
                    ],
                ]),
                "status" => "success",
                "message" => "Notas mapeadas com sucesso"
            ])),
        ]);

        $handler = HandlerStack::create($mock);
        $handler->push($history);
        $client = new Client(['handler' => $handler]);

        // Seta cliente de testes
        Moodle::setClient($client);

        $statusMatricula = $matriculaOfertaDisciplina->mof_situacao_matricula;

        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota1));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota2));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota3));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_recuperacao));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_final));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_conceito));

        $result = $this->repo->mapearNotasAluno($ofertaDisciplina, $matriculaOfertaDisciplina, $this->configuracoesCurso);
        $this->assertEquals('error', $result['status']);

        // Atualiza a partir do DB
        $matriculaOfertaDisciplina = \Modulos\Academico\Models\MatriculaOfertaDisciplina::find($matriculaOfertaDisciplina->mof_id);

        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota1));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota2));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_nota3));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_recuperacao));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_final));
        $this->assertTrue(is_null($matriculaOfertaDisciplina->mof_conceito));

        // Status nao deve mudar
        $this->assertTrue($statusMatricula == $matriculaOfertaDisciplina->mof_situacao_matricula);
    }
}
