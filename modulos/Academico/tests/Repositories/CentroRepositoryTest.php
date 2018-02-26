<?php

use Tests\ModulosTestCase;
use Modulos\Academico\Models\Centro;
use Stevebauman\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Academico\Repositories\CentroRepository;

class CentroRepositoryTest extends ModulosTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->repo = $this->app->make(CentroRepository::class);
        $this->table = 'acd_centros';
    }

    public function testCreate()
    {
        $data = factory(Centro::class)->raw();
        $entry = $this->repo->create($data);

        $this->assertInstanceOf(Centro::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->toArray());
    }

    public function testFind()
    {
        $entry = factory(Centro::class)->create();
        $id = $entry->cen_id;
        $fromRepository = $this->repo->find($id);

        $this->assertInstanceOf(Centro::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepository->toArray());
        $this->assertEquals($entry->toArray(), $fromRepository->toArray());
    }

    public function testUpdate()
    {
        $entry = factory(Centro::class)->create();
        $id = $entry->cen_id;

        $data = $entry->toArray();

        $data['cen_nome'] = "slug";

        $return = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(Centro::class, $fromRepository);
        $this->assertEquals($data, $fromRepository->toArray());
    }

    public function testLists()
    {
        $entries = factory(Centro::class, 2)->create();

        $model = new Centro();
        $expected = $model->pluck('cen_nome', 'cen_id');
        $fromRepository = $this->repo->lists('cen_id', 'cen_nome');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        $entries = factory(Centro::class, 2)->create();

        factory(Centro::class)->create([
            'cen_nome' => 'centro'
        ]);

        $searchResult = $this->repo->search(array(['cen_nome', '=', 'centro']));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(Centro::class, 2)->create();

        $entry = factory(Centro::class)->create([
            'cen_nome' => "centro"
        ]);

        $expected = [
            'cen_id' => $entry->cen_id,
            'cen_nome' => $entry->cen_nome
        ];

        $searchResult = $this->repo->search(array(['cen_nome', '=', "centro"]), ['cen_id', 'cen_nome']);

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
        $created = factory(Centro::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(Centro::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new Centro();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Centro::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Centro::class, 2)->create();

        $sort = [
            'field' => 'cen_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->cen_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Centro::class, 2)->create();
        factory(Centro::class)->create([
            'cen_nome' => 'centro',
        ]);

        $search = [
            [
                'field' => 'cen_nome',
                'type' => '=',
                'term' => 'centro'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('centro', $response->first()->cen_nome);
    }

    public function testPaginateRequest()
    {
        factory(Centro::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'cen_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }
}
