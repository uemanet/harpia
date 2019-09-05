<?php

use Tests\ModulosTestCase;
use Modulos\Integracao\Models\AmbienteServico;
use Stevebauman\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Integracao\Repositories\AmbienteServicoRepository;

class AmbienteServicoRepositoryTest extends ModulosTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->repo = $this->app->make(AmbienteServicoRepository::class);
        $this->table = 'int_ambientes_servicos';
    }

    public function testCreate()
    {
        $data = factory(AmbienteServico::class)->raw();
        $entry = $this->repo->create($data);

        $this->assertInstanceOf(AmbienteServico::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->toArray());
    }

    public function testFind()
    {
        $entry = factory(AmbienteServico::class)->create();
        $id = $entry->asr_id;
        $fromRepository = $this->repo->find($id);

        $this->assertInstanceOf(AmbienteServico::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepository->toArray());
        $this->assertEquals($entry->toArray(), $fromRepository->toArray());
    }

    public function testUpdate()
    {
        $entry = factory(AmbienteServico::class)->create();
        $id = $entry->asr_id;

        $data = $entry->toArray();

        $data['asr_token'] = "newToken";

        $return = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(AmbienteServico::class, $fromRepository);
        $this->assertEquals($data, $fromRepository->toArray());
    }

    public function testDelete()
    {
        $entry = factory(AmbienteServico::class)->create();
        $id = $entry->asr_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        $entries = factory(AmbienteServico::class, 2)->create();

        $model = new AmbienteServico();
        $expected = $model->pluck('asr_token', 'asr_id');
        $fromRepository = $this->repo->lists('asr_id', 'asr_token');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        $entries = factory(AmbienteServico::class, 10)->create();

        factory(AmbienteServico::class)->create([
            'asr_token' => 'findme'
        ]);

        $searchResult = $this->repo->search(array(['asr_token', '=', 'findme']));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(AmbienteServico::class, 10)->create();

        $entry = factory(AmbienteServico::class)->create([
            'asr_token' => 'findme'
        ]);

        $expected = [
            'asr_id' => $entry->asr_id,
            'asr_token' => $entry->asr_token
        ];

        $searchResult = $this->repo->search(array(['asr_token', '=', 'findme']), ['asr_id', 'asr_token']);

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
        $created = factory(AmbienteServico::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(AmbienteServico::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new AmbienteServico();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(AmbienteServico::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(AmbienteServico::class, 2)->create();

        $sort = [
            'field' => 'asr_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->asr_id);
    }

    public function testPaginateWithSearch()
    {
        factory(AmbienteServico::class, 2)->create();
        factory(AmbienteServico::class)->create([
            'asr_ser_id' => 1,
        ]);

        $search = [
            [
                'field' => 'asr_ser_id',
                'type' => '=',
                'term' => 1
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals(1, $response->first()->asr_ser_id);
    }

    public function testPaginateRequest()
    {
        factory(AmbienteServico::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'asr_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }
}
