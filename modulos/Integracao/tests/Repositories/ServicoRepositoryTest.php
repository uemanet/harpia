<?php

use Tests\ModulosTestCase;
use Modulos\Integracao\Models\Servico;
use Modulos\Integracao\Models\AmbienteVirtual;
use Modulos\Integracao\Models\AmbienteServico;
use Uemanet\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Integracao\Repositories\ServicoRepository;

class ServicoRepositoryTest extends ModulosTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->repo = $this->app->make(ServicoRepository::class);
        $this->table = 'int_servicos';
    }

    public function testCreate()
    {
        $data = factory(Servico::class)->raw();
        $entry = $this->repo->create($data);

        $this->assertInstanceOf(Servico::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->toArray());
    }

    public function testFind()
    {
        $entry = factory(Servico::class)->create();
        $id = $entry->ser_id;
        $fromRepository = $this->repo->find($id);

        $this->assertInstanceOf(Servico::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepository->toArray());
        $this->assertEquals($entry->toArray(), $fromRepository->toArray());
    }

    public function testUpdate()
    {
        $entry = factory(Servico::class)->create();
        $id = $entry->ser_id;

        $data = $entry->toArray();

        $data['ser_slug'] = "slug";

        $return = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(Servico::class, $fromRepository);
        $this->assertEquals($data, $fromRepository->toArray());
    }

    public function testDelete()
    {
        $entry = factory(Servico::class)->create();
        $id = $entry->ser_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        $entries = factory(Servico::class, 2)->create();

        $model = new Servico();
        $expected = $model->pluck('ser_nome', 'ser_id');
        $fromRepository = $this->repo->lists('ser_id', 'ser_nome');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        $entries = factory(Servico::class, 2)->create();

        factory(Servico::class)->create([
            'ser_slug' => 'slug_name'
        ]);

        $searchResult = $this->repo->search(array(['ser_slug', '=', 'slug_name']));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(Servico::class, 2)->create();

        $entry = factory(Servico::class)->create([
            'ser_slug' => "slug_to_find"
        ]);

        $expected = [
            'ser_id' => $entry->ser_id,
            'ser_slug' => $entry->ser_slug
        ];

        $searchResult = $this->repo->search(array(['ser_slug', '=', "slug_to_find"]), ['ser_id', 'ser_slug']);

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
        $created = factory(Servico::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(Servico::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new Servico();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Servico::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Servico::class, 2)->create();

        $sort = [
            'field' => 'ser_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->ser_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Servico::class, 2)->create();
        factory(Servico::class)->create([
            'ser_slug' => 'slug_to_search',
        ]);

        $search = [
            [
                'field' => 'ser_slug',
                'type' => '=',
                'term' => 'slug_to_search'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('slug_to_search', $response->first()->ser_slug);
    }

    public function testPaginateRequest()
    {
        factory(Servico::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'ser_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }

    public function testVerifyIfExistsAmbienteServico()
    {
        $ambiente = factory(AmbienteVirtual::class)->create();
        $servico = factory(Servico::class)->create();

        // Test without service
        $this->assertFalse($this->repo->verifyIfExistsAmbienteServico($ambiente->amb_id, $servico->ser_id));

        // Test with service
        factory(AmbienteServico::class)->create([
            'asr_amb_id' => $ambiente->amb_id,
            'asr_ser_id' => $servico->ser_id,
        ]);

        $this->assertTrue($this->repo->verifyIfExistsAmbienteServico($ambiente->amb_id, $servico->ser_id));
    }
}
