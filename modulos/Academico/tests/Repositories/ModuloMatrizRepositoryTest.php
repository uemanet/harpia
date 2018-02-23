<?php

use Tests\ModulosTestCase;
use Modulos\Academico\Models\ModuloMatriz;
use Illuminate\Pagination\LengthAwarePaginator;
use Stevebauman\EloquentTable\TableCollection;
use Modulos\Academico\Repositories\ModuloMatrizRepository;

class ModuloMatrizRepositoryTest extends ModulosTestCase
{
    protected $repo;

    public function setUp()
    {
        parent::setUp();
        $this->repo = $this->app->make(ModuloMatrizRepository::class);
        $this->table = 'acd_modulos_matrizes';
    }

    public function testCreate()
    {
        $data = factory(ModuloMatriz::class)->raw();
        $entry = $this->repo->create($data);

        $this->assertInstanceOf(ModuloMatriz::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->toArray());
    }

    public function testFind()
    {
        $entry = factory(ModuloMatriz::class)->create();
        $id = $entry->mdo_id;
        $fromRepository = $this->repo->find($id);

        $this->assertInstanceOf(ModuloMatriz::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepository->toArray());
        $this->assertEquals($entry->toArray(), $fromRepository->toArray());
    }

    public function testUpdate()
    {
        $entry = factory(ModuloMatriz::class)->create();
        $id = $entry->mdo_id;

        $data = $entry->toArray();

        $data['mdo_nome'] = 'Módulo 1';

        $return = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(ModuloMatriz::class, $fromRepository);
        $this->assertEquals($data, $fromRepository->toArray());
    }

    public function testDelete()
    {
        $entry = factory(ModuloMatriz::class)->create();
        $id = $entry->mdo_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        $entries = factory(ModuloMatriz::class, 2)->create();

        $model = new ModuloMatriz();
        $expected = $model->pluck('mdo_nome', 'mdo_id');
        $fromRepository = $this->repo->lists('mdo_id', 'mdo_nome');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        $entries = factory(ModuloMatriz::class, 2)->create();

        factory(ModuloMatriz::class)->create([
            'mdo_nome' => 'search_mdo_nome'
        ]);

        $searchResult = $this->repo->search(array(['mdo_nome', '=', 'search_mdo_nome']));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(ModuloMatriz::class, 2)->create();

        $entry = factory(ModuloMatriz::class)->create([
            'mdo_nome' => "mdo_nome_to_find"
        ]);

        $expected = [
            'mdo_id' => $entry->mdo_id,
            'mdo_nome' => $entry->mdo_nome
        ];

        $searchResult = $this->repo->search(array(['mdo_nome', '=', "mdo_nome_to_find"]), ['mdo_id', 'mdo_nome']);

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
        $created = factory(ModuloMatriz::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(ModuloMatriz::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new ModuloMatriz();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(ModuloMatriz::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(ModuloMatriz::class, 2)->create();

        $sort = [
            'field' => 'mdo_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->mdo_id);
    }

    public function testPaginateWithSearch()
    {
        factory(ModuloMatriz::class, 2)->create();
        factory(ModuloMatriz::class)->create([
            'mdo_nome' => 'mdo_nome_to_search',
        ]);

        $search = [
            [
                'field' => 'mdo_nome',
                'type' => '=',
                'term' => 'mdo_nome_to_search'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('mdo_nome_to_search', $response->first()->mdo_nome);
    }

    public function testPaginateRequest()
    {
        factory(ModuloMatriz::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'mdo_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }

    public function testVerifyNameMatriz()
    {
        $matriz = factory(Modulos\Academico\Models\MatrizCurricular::class)->create();

        $moduloMatriz = factory(Modulos\Academico\Models\ModuloMatriz::class)->create([
            'mdo_mtc_id' => $matriz->mtc_id,
            'mdo_nome' => 'Módulo 1',
        ]);

        $result = $this->repo->verifyNameMatriz('Módulo 2', $matriz->mtc_id);

        $this->assertFalse($result);
    }

    public function testVerifyNameMatrizWithNameEqual()
    {
        $matriz = factory(Modulos\Academico\Models\MatrizCurricular::class)->create();

        $moduloMatriz = factory(Modulos\Academico\Models\ModuloMatriz::class)->create([
            'mdo_mtc_id' => $matriz->mtc_id,
            'mdo_nome' => 'Módulo 1',
        ]);

        $result = $this->repo->verifyNameMatriz('Módulo 1', $matriz->mtc_id);

        $this->assertTrue($result);
    }

    public function testVerifyNameMatrizWithModuloId()
    {
        $matriz = factory(Modulos\Academico\Models\MatrizCurricular::class)->create();

        $moduloMatriz = factory(Modulos\Academico\Models\ModuloMatriz::class)->create([
            'mdo_mtc_id' => $matriz->mtc_id,
            'mdo_nome' => 'Módulo 1',
        ]);

        factory(Modulos\Academico\Models\ModuloMatriz::class, 2)->create([
            'mdo_mtc_id' => $matriz->mtc_id
        ]);

        $result = $this->repo->verifyNameMatriz('Módulo 1', $matriz->mtc_id, $moduloMatriz->mdo_id);

        $this->assertFalse($result);
    }

    public function testGetAllModulosByMatriz()
    {
        $matriz = factory(Modulos\Academico\Models\MatrizCurricular::class)->create();

        $moduloMatriz = factory(Modulos\Academico\Models\ModuloMatriz::class, 2)->create([
            'mdo_mtc_id' => $matriz->mtc_id,
        ]);

        $result = $this->repo->getAllModulosByMatriz($matriz->mtc_id);

        $this->assertNotEmpty($result);
    }
}
