<?php

use Tests\ModulosTestCase;
use Modulos\Academico\Models\Departamento;
use Stevebauman\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Academico\Repositories\DepartamentoRepository;

class DepartamentoRepositoryTest extends ModulosTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->repo = $this->app->make(DepartamentoRepository::class);
        $this->table = 'acd_departamentos';
    }

    public function testCreate()
    {
        $data = factory(Departamento::class)->raw();
        $entry = $this->repo->create($data);

        $this->assertInstanceOf(Departamento::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->toArray());
    }

    public function testFind()
    {
        $entry = factory(Departamento::class)->create();
        $id = $entry->dep_id;
        $fromRepository = $this->repo->find($id);

        $this->assertInstanceOf(Departamento::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepository->toArray());
        $this->assertEquals($entry->toArray(), $fromRepository->toArray());
    }

    public function testUpdate()
    {
        $entry = factory(Departamento::class)->create();
        $id = $entry->dep_id;

        $data = $entry->toArray();

        $data['dep_nome'] = "slug";

        $return = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(Departamento::class, $fromRepository);
        $this->assertEquals($data, $fromRepository->toArray());
    }

    public function testDelete()
    {
        $entry = factory(Departamento::class)->create();
        $id = $entry->dep_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        $entries = factory(Departamento::class, 2)->create();

        $model = new Departamento();
        $expected = $model->pluck('dep_nome', 'dep_id');
        $fromRepository = $this->repo->lists('dep_id', 'dep_nome');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        $entries = factory(Departamento::class, 2)->create();

        factory(Departamento::class)->create([
            'dep_nome' => 'departamento'
        ]);

        $searchResult = $this->repo->search(array(['dep_nome', '=', 'departamento']));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(Departamento::class, 2)->create();

        $entry = factory(Departamento::class)->create([
            'dep_nome' => "departamento"
        ]);

        $expected = [
            'dep_id' => $entry->dep_id,
            'dep_nome' => $entry->dep_nome
        ];

        $searchResult = $this->repo->search(array(['dep_nome', '=', "departamento"]), ['dep_id', 'dep_nome']);

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
        $created = factory(Departamento::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(Departamento::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new Departamento();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Departamento::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Departamento::class, 2)->create();

        $sort = [
            'field' => 'dep_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->dep_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Departamento::class, 2)->create();
        factory(Departamento::class)->create([
            'dep_nome' => 'departamento',
        ]);

        $search = [
            [
                'field' => 'dep_nome',
                'type' => '=',
                'term' => 'departamento'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('departamento', $response->first()->dep_nome);
    }

    public function testPaginateRequest()
    {
        factory(Departamento::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'dep_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }
}
