<?php

use Tests\ModulosTestCase;
use Modulos\Academico\Models\Disciplina;
use Stevebauman\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Academico\Repositories\DisciplinaRepository;

class DisciplinaRepositoryTest extends ModulosTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->repo = $this->app->make(DisciplinaRepository::class);
        $this->table = 'acd_disciplinas';
    }

    private function mockData()
    {
        $curso = factory(Modulos\Academico\Models\Curso::class)->create([
            'crs_nvc_id' => 2,
        ]);

        $matrizCurricular = factory(Modulos\Academico\Models\MatrizCurricular::class)->create([
            'mtc_crs_id' => $curso->crs_id
        ]);

        $modulosMatriz = factory(Modulos\Academico\Models\ModuloMatriz::class, 2)->create([
            'mdo_mtc_id' => $matrizCurricular->mtc_id
        ]);

        // Disciplinas para o curso
        $disciplinas = factory(Modulos\Academico\Models\Disciplina::class, 6)->create([
            'dis_nvc_id' => $curso->crs_nvc_id
        ]);

        $modulosDisciplina = new \Illuminate\Support\Collection();
        foreach ($modulosMatriz as $key => $moduloMatriz) {
            for ($i = $key * 3; $i < 3 * ($key + 1); $i++) {
                $modulosDisciplina[] = factory(Modulos\Academico\Models\ModuloDisciplina::class)->create([
                    'mdc_dis_id' => $disciplinas[$i]->dis_id,
                    'mdc_mdo_id' => $moduloMatriz->mdo_id,
                    'mdc_tipo_disciplina' => 'obrigatoria'
                ]);
            }
        }

        return [$curso, $matrizCurricular, $modulosMatriz];
    }

    public function testCreate()
    {
        $data = factory(Disciplina::class)->raw();
        $entry = $this->repo->create($data);

        $this->assertInstanceOf(Disciplina::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->toArray());
    }

    public function testFind()
    {
        $entry = factory(Disciplina::class)->create();
        $id = $entry->dis_id;
        $fromRepository = $this->repo->find($id);

        $this->assertInstanceOf(Disciplina::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepository->toArray());
        $this->assertEquals($entry->toArray(), $fromRepository->toArray());
    }

    public function testUpdate()
    {
        $entry = factory(Disciplina::class)->create();
        $id = $entry->dis_id;

        $data = $entry->toArray();

        $data['dis_nome'] = "chair";

        $return = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(Disciplina::class, $fromRepository);
        $this->assertEquals($data, $fromRepository->toArray());
    }

    public function testDelete()
    {
        $entry = factory(Disciplina::class)->create();
        $id = $entry->dis_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        $entries = factory(Disciplina::class, 2)->create();

        $model = new Disciplina();
        $expected = $model->pluck('dis_nome', 'dis_id');
        $fromRepository = $this->repo->lists('dis_id', 'dis_nome');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        $entries = factory(Disciplina::class, 2)->create();

        factory(Disciplina::class)->create([
            'dis_nome' => 'lorem ipsum'
        ]);

        $searchResult = $this->repo->search(array(['dis_nome', '=', 'lorem ipsum']));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(Disciplina::class, 2)->create();

        $entry = factory(Disciplina::class)->create([
            'dis_nome' => "lorem ipsum"
        ]);

        $expected = [
            'dis_id' => $entry->dis_id,
            'dis_nome' => $entry->dis_nome
        ];

        $searchResult = $this->repo->search(array(['dis_nome', '=', "lorem ipsum"]), ['dis_id', 'dis_nome']);

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
        $created = factory(Disciplina::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(Disciplina::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new Disciplina();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Disciplina::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Disciplina::class, 2)->create();

        $sort = [
            'field' => 'dis_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->dis_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Disciplina::class, 2)->create();
        factory(Disciplina::class)->create([
            'dis_nome' => 'lorem ipsum',
        ]);

        $search = [
            [
                'field' => 'dis_nome',
                'type' => '=',
                'term' => 'lorem ipsum'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('lorem ipsum', $response->first()->dis_nome);
    }

    public function testPaginateWithSearchAndSort()
    {
        factory(Disciplina::class, 2)->create([
            'dis_nome' => 'lorem ipsum' . bin2hex(random_bytes(5)),
        ]);

        factory(Disciplina::class)->create([
            'dis_nvc_id' => 1,
            'dis_nome' => 'lorem ipsum',
        ]);

        $sort = [
            'field' => 'dis_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'dis_nome',
                'type' => 'like',
                'term' => 'lorem ipsum'
            ],
            [
                'field' => 'dis_nvc_id',
                'type' => '=',
                'term' => 1
            ]
        ];

        $response = $this->repo->paginate($sort, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('lorem ipsum', $response->first()->dis_nome);
    }

    public function testPaginateRequest()
    {
        factory(Disciplina::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'dis_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }

    public function testValidacao()
    {
        $disciplinas = factory(Disciplina::class, 10)->create();

        // Verificar dados que nao existem no banco
        $data = factory(Disciplina::class)->raw();

        $result = $this->repo->validacao($data);
        $this->assertTrue($result);

        $result = $this->repo->validacao($data, random_int(1, 10));
        $this->assertTrue($result);

        // Verifica com dados existentes no banco

        $toCheck = $disciplinas->random();
        $data = $toCheck->toArray();

        unset($data['dis_id']);

        $result = $this->repo->validacao($data); // Dados repetidos !!
        $this->assertFalse($result);

        $result = $this->repo->validacao($data, $toCheck->dis_id); // Caso de update
        $this->assertTrue($result);
    }

    public function testBuscar()
    {
        list($curso, $matrizCurricular) = $this->mockData();

        $disciplinasRainbow = factory(Disciplina::class, 4)->create([
            'dis_nvc_id' => $curso->crs_nvc_id,
            'dis_nome' => 'rainbow ' . bin2hex(random_bytes(10))
        ]);

        $disciplinasRose = factory(Disciplina::class, 3)->create([
            'dis_nvc_id' => $curso->crs_nvc_id,
            'dis_nome' => 'rose ' . bin2hex(random_bytes(10))
        ]);

        $response = $this->repo->buscar($matrizCurricular->mtc_id, "rainbow");

        $this->assertEquals(4, $response->count());
        $this->assertStringStartsWith('rainbow', $response->random()->dis_nome);

        $response = $this->repo->buscar($matrizCurricular->mtc_id, "rose");

        $this->assertEquals(3, $response->count());
        $this->assertStringStartsWith('rose', $response->random()->dis_nome);
    }

    public function testGetDisciplinasModulosAnteriores()
    {
        list(, $matrizCurricular, $modulosMatriz) = $this->mockData();

        $result = $this->repo->getDisciplinasModulosAnteriores($matrizCurricular->mtc_id, $modulosMatriz->last()->mdo_id);

        $disciplinasAnteriores = $modulosMatriz->first()->disciplinas;

        $this->assertEquals($disciplinasAnteriores->count(), $result->count());

        foreach ($disciplinasAnteriores as $key => $disciplina) {
            $this->assertEquals($disciplina->dis_nome, $result[$key]->dis_nome);
        }
    }
}
