<?php

use Tests\ModulosTestCase;
use Modulos\Academico\Models\Turma;
use Modulos\Integracao\Models\AmbienteTurma;
use Modulos\Integracao\Models\AmbienteVirtual;
use Stevebauman\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;

class AmbienteVirtualRepositoryTest extends ModulosTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->repo = $this->app->make(AmbienteVirtualRepository::class);
        $this->table = 'int_ambientes_virtuais';
    }

    public function testCreate()
    {
        $data = factory(AmbienteVirtual::class)->raw();
        $entry = $this->repo->create($data);

        $this->assertInstanceOf(AmbienteVirtual::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->toArray());
    }

    public function testFind()
    {
        $entry = factory(AmbienteVirtual::class)->create();
        $id = $entry->amb_id;
        $fromRepository = $this->repo->find($id);

        $this->assertInstanceOf(AmbienteVirtual::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepository->toArray());
        $this->assertEquals($entry->toArray(), $fromRepository->toArray());
    }

    public function testUpdate()
    {
        $entry = factory(AmbienteVirtual::class)->create();
        $id = $entry->amb_id;

        $data = $entry->toArray();

        $data['amb_nome'] = "New name";

        $return = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(AmbienteVirtual::class, $fromRepository);
        $this->assertEquals($data, $fromRepository->toArray());
    }

    public function testDelete()
    {
        $entry = factory(AmbienteVirtual::class)->create();
        $id = $entry->amb_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        $entries = factory(AmbienteVirtual::class, 2)->create();

        $model = new AmbienteVirtual();
        $expected = $model->pluck('amb_nome', 'amb_id');
        $fromRepository = $this->repo->lists('amb_id', 'amb_nome');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        $entries = factory(AmbienteVirtual::class, 2)->create();

        factory(AmbienteVirtual::class)->create([
            'amb_versao' => "3.1"
        ]);

        $searchResult = $this->repo->search(array(['amb_versao', '=', "3.1"]));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(AmbienteVirtual::class, 2)->create();

        $entry = factory(AmbienteVirtual::class)->create([
            'amb_nome' => "New name"
        ]);

        $expected = [
            'amb_id' => $entry->amb_id,
            'amb_nome' => $entry->amb_nome
        ];

        $searchResult = $this->repo->search(array(['amb_nome', '=', "New name"]), ['amb_id', 'amb_nome']);

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
        $created = factory(AmbienteVirtual::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(AmbienteVirtual::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new AmbienteVirtual();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(AmbienteVirtual::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(AmbienteVirtual::class, 2)->create();

        $sort = [
            'field' => 'amb_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->amb_id);
    }

    public function testPaginateWithSearch()
    {
        factory(AmbienteVirtual::class, 2)->create();
        factory(AmbienteVirtual::class)->create([
            'amb_nome' => 1,
        ]);

        $search = [
            [
                'field' => 'amb_nome',
                'type' => '=',
                'term' => 1
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals(1, $response->first()->amb_nome);
    }

    public function testPaginateRequest()
    {
        factory(AmbienteVirtual::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'amb_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }

    public function testGetAmbienteByTurma()
    {
        $turma = factory(Turma::class)->create();
        $ambienteVirtual = factory(AmbienteVirtual::class)->create();

        factory(AmbienteTurma::class)->create([
            'atr_trm_id' => $turma->trm_id,
            'atr_amb_id' => $ambienteVirtual->amb_id
        ]);

        factory(\Modulos\Integracao\Models\AmbienteServico::class)->create([
            'asr_amb_id' => $ambienteVirtual->amb_id,
        ]);

        $fromRepository = $this->repo->getAmbienteByTurma($turma->trm_id);

        $this->assertInstanceOf(AmbienteVirtual::class, $fromRepository);
        $this->assertEquals($ambienteVirtual->amb_id, $fromRepository->amb_id);
    }
}
