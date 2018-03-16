<?php
declare(strict_types=1);

use Tests\ModulosTestCase;
use Illuminate\Support\Facades\Schema;
use Modulos\Integracao\Models\MapeamentoNota;
use Stevebauman\EloquentTable\TableCollection;
use Modulos\Academico\Models\OfertaDisciplina;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Integracao\Repositories\MapeamentoNotasRepository;

class MapeamentoNotasRepositoryTest extends ModulosTestCase
{
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
            'min_id_final' => random_int(1, 2550)
        ];
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

        $result = $this->repo->calcularMedia($data, $this->configuracoesCurso, 'Conceitual');

        $this->assertEquals($result['mof_situacao_matricula'], 'aprovado_media');
    }

    public function testIfAlunoReprovadoConceito()
    {
        $data = [
            'mof_conceito' => 'Insuficiente'
        ];

        $result = $this->repo->calcularMedia($data, $this->configuracoesCurso, 'Conceitual');

        $this->assertEquals($result['mof_situacao_matricula'], 'reprovado_media');
    }

    public function testIfAlunoAprovadoMedia()
    {
        $data = [
            'mof_nota1' => 7.0,
            'mof_nota2' => 7.0,
            'mof_nota3' => 7.0
        ];

        $result = $this->repo->calcularMedia($data, $this->configuracoesCurso);

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

        $result = $this->repo->calcularMedia($data, $this->configuracoesCurso);

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

        $result = $this->repo->calcularMedia($data, $this->configuracoesCurso);

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

        $result = $this->repo->calcularMedia($data, $this->configuracoesCurso);

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

        $result = $this->repo->calcularMedia($data, $this->configuracoesCurso);

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

        $result = $this->repo->calcularMedia($data, $this->configuracoesCurso);

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

        $result = $this->repo->calcularMedia($data, $this->configuracoesCurso);

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

        $result = $this->repo->calcularMedia($data, $this->configuracoesCurso);

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

        $result = $this->repo->calcularMedia($data, $this->configuracoesCurso);

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

        $result = $this->repo->calcularMedia($data, $this->configuracoesCurso);

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

        $result = $this->repo->calcularMedia($data, $this->configuracoesCurso);

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

        $result = $this->repo->calcularMedia($data, $this->configuracoesCurso);

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
}
