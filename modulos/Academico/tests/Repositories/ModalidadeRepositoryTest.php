<?php

use Tests\ModulosTestCase;
use Modulos\Academico\Models\Modalidade;
use Stevebauman\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Academico\Repositories\ModalidadeRepository;

class ModalidadeRepositoryTest extends ModulosTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->repo = $this->app->make(ModalidadeRepository::class);
        $this->table = 'acd_modalidades';
    }

    public function testCreate()
    {
        $data = factory(Modalidade::class)->raw();
        $entry = $this->repo->create($data);

        $this->assertInstanceOf(Modalidade::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->toArray());
    }

    public function testFind()
    {
        $entry = factory(Modalidade::class)->create();
        $id = $entry->mdl_id;
        $fromRepository = $this->repo->find($id);

        $this->assertInstanceOf(Modalidade::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepository->toArray());
        $this->assertEquals($entry->toArray(), $fromRepository->toArray());
    }

    public function testUpdate()
    {
        $entry = factory(Modalidade::class)->create();
        $id = $entry->mdl_id;

        $data = $entry->toArray();

        $data['mdl_nome'] = "slug";

        $return = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(Modalidade::class, $fromRepository);
        $this->assertEquals($data, $fromRepository->toArray());
    }

    public function testDelete()
    {
        $entry = factory(Modalidade::class)->create();
        $id = $entry->mdl_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        $entries = factory(Modalidade::class, 2)->create();

        $model = new Modalidade();
        $expected = $model->pluck('mdl_nome', 'mdl_id');
        $fromRepository = $this->repo->lists('mdl_id', 'mdl_nome');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        $entries = factory(Modalidade::class, 2)->create();

        factory(Modalidade::class)->create([
            'mdl_nome' => 'mdlartamento'
        ]);

        $searchResult = $this->repo->search(array(['mdl_nome', '=', 'mdlartamento']));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(Modalidade::class, 2)->create();

        $entry = factory(Modalidade::class)->create([
            'mdl_nome' => "mdlartamento"
        ]);

        $expected = [
            'mdl_id' => $entry->mdl_id,
            'mdl_nome' => $entry->mdl_nome
        ];

        $searchResult = $this->repo->search(array(['mdl_nome', '=', "mdlartamento"]), ['mdl_id', 'mdl_nome']);

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
        $created = factory(Modalidade::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(Modalidade::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new Modalidade();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Modalidade::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Modalidade::class, 2)->create();

        $sort = [
            'field' => 'mdl_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->mdl_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Modalidade::class, 2)->create();
        factory(Modalidade::class)->create([
            'mdl_nome' => 'mdlartamento',
        ]);

        $search = [
            [
                'field' => 'mdl_nome',
                'type' => '=',
                'term' => 'mdlartamento'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('mdlartamento', $response->first()->mdl_nome);
    }

    public function testPaginateRequest()
    {
        factory(Modalidade::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'mdl_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }
}
