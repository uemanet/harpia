<?php

use Harpia\Util\Util;
use Tests\ModulosTestCase;
use Modulos\Academico\Models\Polo;
use Uemanet\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Academico\Repositories\PoloRepository;

class PoloRepositoryTest extends ModulosTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->repo = $this->app->make(PoloRepository::class);
        $this->table = 'acd_polos';
    }

    public function testCreate()
    {
        $data = factory(Polo::class)->raw();
        $entry = $this->repo->create($data);

        $this->assertInstanceOf(Polo::class, $entry);

        $this->assertDatabaseHas($this->table, $entry->toArray());
    }

    public function testFind()
    {
        $entry = factory(Polo::class)->create();
        $id = $entry->pol_id;
        $fromRepository = $this->repo->find($id);

        $this->assertInstanceOf(Polo::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepository->toArray());
        $this->assertEquals($entry->toArray(), $fromRepository->toArray());
    }

    public function testUpdate()
    {
        $entry = factory(Polo::class)->create();
        $id = $entry->pol_id;

        $data = $entry->toArray();

        $data['pol_nome'] = "slug";

        $return = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(Polo::class, $fromRepository);
        $this->assertEquals($data, $fromRepository->toArray());
    }

    public function testDelete()
    {
        $entry = factory(Polo::class)->create();
        $id = $entry->pol_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        $entries = factory(Polo::class, 2)->create();

        $model = new Polo();
        $expected = $model->pluck('pol_nome', 'pol_id');
        $fromRepository = $this->repo->lists('pol_id', 'pol_nome');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        $entries = factory(Polo::class, 2)->create();

        factory(Polo::class)->create([
            'pol_nome' => 'polo'
        ]);

        $searchResult = $this->repo->search(array(['pol_nome', '=', 'polo']));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(Polo::class, 2)->create();

        $entry = factory(Polo::class)->create([
            'pol_nome' => "polo"
        ]);

        $expected = [
            'pol_id' => $entry->pol_id,
            'pol_nome' => $entry->pol_nome
        ];

        $searchResult = $this->repo->search(array(['pol_nome', '=', "polo"]), ['pol_id', 'pol_nome']);

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
        $created = factory(Polo::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(Polo::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new Polo();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Polo::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Polo::class, 2)->create();

        $sort = [
            'field' => 'pol_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->pol_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Polo::class, 2)->create();
        factory(Polo::class)->create([
            'pol_nome' => 'polo',
        ]);

        $search = [
            [
                'field' => 'pol_nome',
                'type' => '=',
                'term' => 'polo'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('polo', $response->first()->pol_nome);
    }

    public function testPaginateRequest()
    {
        factory(Polo::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'pol_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }
}
