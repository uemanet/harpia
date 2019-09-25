<?php

use Tests\ModulosTestCase;
use Modulos\Integracao\Models\AmbienteTurma;
use Uemanet\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Integracao\Repositories\AmbienteTurmaRepository;

class AmbienteTurmaRepositoryTest extends ModulosTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->repo = $this->app->make(AmbienteTurmaRepository::class);
        $this->table = 'int_ambientes_turmas';
    }

    public function testCreate()
    {
        $data = factory(AmbienteTurma::class)->raw();
        $entry = $this->repo->create($data);

        $this->assertInstanceOf(AmbienteTurma::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->toArray());
    }

    public function testFind()
    {
        $entry = factory(AmbienteTurma::class)->create();
        $id = $entry->atr_id;
        $fromRepository = $this->repo->find($id);

        $this->assertInstanceOf(AmbienteTurma::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepository->toArray());
        $this->assertEquals($entry->toArray(), $fromRepository->toArray());
    }

    public function testUpdate()
    {
        $entry = factory(AmbienteTurma::class)->create();
        $id = $entry->atr_id;

        $data = $entry->toArray();

        $data['atr_trm_id'] = 2;

        $return = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(AmbienteTurma::class, $fromRepository);
        $this->assertEquals($data, $fromRepository->toArray());
    }

    public function testDelete()
    {
        $entry = factory(AmbienteTurma::class)->create();
        $id = $entry->atr_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        $entries = factory(AmbienteTurma::class, 2)->create();

        $model = new AmbienteTurma();
        $expected = $model->pluck('atr_trm_id', 'atr_id');
        $fromRepository = $this->repo->lists('atr_id', 'atr_trm_id');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        $entries = factory(AmbienteTurma::class, 2)->create();

        factory(AmbienteTurma::class)->create([
            'atr_trm_id' => 3
        ]);

        $searchResult = $this->repo->search(array(['atr_trm_id', '=', 2]));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(AmbienteTurma::class, 2)->create();

        $entry = factory(AmbienteTurma::class)->create([
            'atr_trm_id' => 3
        ]);

        $expected = [
            'atr_id' => $entry->atr_id,
            'atr_trm_id' => $entry->atr_trm_id
        ];

        $searchResult = $this->repo->search(array(['atr_trm_id', '=', 3]), ['atr_id', 'atr_trm_id']);

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
        $created = factory(AmbienteTurma::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(AmbienteTurma::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new AmbienteTurma();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(AmbienteTurma::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(AmbienteTurma::class, 2)->create();

        $sort = [
            'field' => 'atr_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->atr_id);
    }

    public function testPaginateWithSearch()
    {
        factory(AmbienteTurma::class, 2)->create();
        factory(AmbienteTurma::class)->create([
            'atr_trm_id' => 1,
        ]);

        $search = [
            [
                'field' => 'atr_trm_id',
                'type' => '=',
                'term' => 1
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals(1, $response->first()->atr_trm_id);
    }

    public function testPaginateRequest()
    {
        factory(AmbienteTurma::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'atr_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }
}
