<?php

use Tests\ModulosTestCase;
use Modulos\Integracao\Models\Sincronizacao;
use Stevebauman\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Integracao\Repositories\SincronizacaoRepository;

class SincronizacaoRepositoryTest extends ModulosTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->repo = $this->app->make(SincronizacaoRepository::class);
        $this->table = 'int_sync_moodle';
    }

    public function testCreate()
    {
        $data = factory(Sincronizacao::class)->raw();
        $entry = $this->repo->create($data);

        $this->assertInstanceOf(Sincronizacao::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->toArray());
    }

    public function testFind()
    {
        $entry = factory(Sincronizacao::class)->create();
        $id = $entry->sym_id;
        $fromRepository = $this->repo->find($id);

        $this->assertInstanceOf(Sincronizacao::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepository->toArray());
        $this->assertEquals($entry->toArray(), $fromRepository->toArray());
    }

    public function testUpdate()
    {
        $entry = factory(Sincronizacao::class)->create();
        $id = $entry->sym_id;

        $data = $entry->toArray();

        $data['sym_mensagem'] = "Message";

        $return = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(Sincronizacao::class, $fromRepository);
        $this->assertEquals($data, $fromRepository->toArray());
    }

    public function testDelete()
    {
        $entry = factory(Sincronizacao::class)->create();
        $id = $entry->sym_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        $entries = factory(Sincronizacao::class, 2)->create();

        $model = new Sincronizacao();
        $expected = $model->pluck('sym_table', 'sym_id');
        $fromRepository = $this->repo->lists('sym_id', 'sym_table');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        $entries = factory(Sincronizacao::class, 2)->create();

        factory(Sincronizacao::class)->create([
            'sym_status' => 4
        ]);

        $searchResult = $this->repo->search(array(['sym_status', '=', 4]));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(Sincronizacao::class, 2)->create();

        $entry = factory(Sincronizacao::class)->create([
            'sym_mensagem' => "message_to_find"
        ]);

        $expected = [
            'sym_id' => $entry->sym_id,
            'sym_mensagem' => $entry->sym_mensagem
        ];

        $searchResult = $this->repo->search(array(['sym_mensagem', '=', "message_to_find"]), ['sym_id', 'sym_mensagem']);

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
        $created = factory(Sincronizacao::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(Sincronizacao::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new Sincronizacao();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Sincronizacao::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Sincronizacao::class, 2)->create();

        $sort = [
            'field' => 'sym_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->sym_id);
    }

    public function testFindBy()
    {
        factory(Sincronizacao::class, 2)->create([
            'sym_status' => 2
        ]);

        factory(Sincronizacao::class)->create([
            'sym_table' => 'table_to_search',
            'sym_status' => 3
        ]);

        $result = $this->repo->findBy([
            'sym_status' => 1
        ]);

        $this->assertEquals(0, $result->count());

        $result = $this->repo->findBy([
            'sym_table' => 'table_to_search',
            'sym_status' => 3
        ]);

        $this->assertEquals(1, $result->count());

        $result = $this->repo->findBy([]);
        $this->assertEquals(3, $result->count());
    }

    public function testPaginateWithSearch()
    {
        factory(Sincronizacao::class, 2)->create();
        factory(Sincronizacao::class)->create([
            'sym_table' => 'table_to_search',
            'sym_status' => 3
        ]);

        $search = [
            [
                'field' => 'sym_table',
                'type' => 'like',
                'term' => 'table_to_search'
            ],
            [
                'field' => 'sym_table',
                'type' => '=',
                'term' => 'table_to_search'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('table_to_search', $response->first()->sym_table);
    }

    public function testPaginateRequest()
    {
        factory(Sincronizacao::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'sym_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }

    public function testUpdateSyncMoodle()
    {
        $data = factory(Modulos\Integracao\Models\Sincronizacao::class)->create([
            'sym_table' => 'gra_pessoas',
            'sym_table_id' => 1,
            'sym_action' => 'UPDATE',
        ]);

        $updateArray = $data->toArray();
        $updateArray['sym_mensagem'] = 'abcde_edcba';

        $response = $this->repo->updateSyncMoodle($updateArray);

        $this->assertEquals($data->sym_id, $response);
    }
}
