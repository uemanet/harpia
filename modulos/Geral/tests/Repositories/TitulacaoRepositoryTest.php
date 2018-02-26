<?php

use Tests\ModulosTestCase;
use Modulos\Geral\Models\Titulacao;
use Stevebauman\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Geral\Repositories\TitulacaoRepository;

class TitulacaoRepositoryTest extends ModulosTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->repo = $this->app->make(TitulacaoRepository::class);
        $this->table = 'gra_titulacoes';
    }

    public function testCreate()
    {
        $data = factory(Titulacao::class)->raw();
        $entry = $this->repo->create($data);

        $this->assertInstanceOf(Titulacao::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->getOriginal());
    }

    public function testFind()
    {
        $entry = factory(Titulacao::class)->create();

        $id = $entry->tit_id;
        $fromRepository = $this->repo->find($id);

        $this->assertInstanceOf(Titulacao::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepository->toArray());
        $this->assertEquals($entry->toArray(), $fromRepository->toArray());
    }

    public function testUpdate()
    {
        $entry = factory(Titulacao::class)->create();
        $id = $entry->tit_id;

        $data = $entry->toArray();
        $data['tit_nome'] = "especialista";

        $return = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(Titulacao::class, $fromRepository);
        $this->assertEquals($data, $fromRepository->toArray());
    }

    public function testDelete()
    {
        $entry = factory(Titulacao::class)->create();
        $id = $entry->tit_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        $entries = factory(Titulacao::class, 2)->create();

        $model = new Titulacao();
        $expected = $model->pluck('tit_nome', 'tit_id');
        $fromRepository = $this->repo->lists('tit_id', 'tit_nome');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        $entries = factory(Titulacao::class, 2)->create();

        factory(Titulacao::class)->create([
            'tit_nome' => 'doutorado'
        ]);

        $searchResult = $this->repo->search(array(['tit_nome', '=', 'doutorado']));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(Titulacao::class, 2)->create();

        $entry = factory(Titulacao::class)->create([
            'tit_nome' => "bachelor"
        ]);

        $expected = [
            'tit_id' => $entry->tit_id,
            'tit_nome' => $entry->tit_nome
        ];

        $searchResult = $this->repo->search(array(['tit_nome', '=', "bachelor"]), ['tit_id', 'tit_nome']);

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
        $created = factory(Titulacao::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(Titulacao::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new Titulacao();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Titulacao::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Titulacao::class, 2)->create();

        $sort = [
            'field' => 'tit_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->tit_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Titulacao::class, 2)->create();
        factory(Titulacao::class)->create([
            'tit_nome' => 'bachelor',
        ]);

        $search = [
            [
                'field' => 'tit_nome',
                'type' => '=',
                'term' => 'bachelor'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('bachelor', $response->first()->tit_nome);
    }

    public function testPaginateRequest()
    {
        factory(Titulacao::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'tit_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }

    public function testVerifyTitulacao()
    {
        factory(Titulacao::class, 10)->create();

        $this->assertEquals(null, $this->repo->verifyTitulacao('bachelor'));

        factory(Titulacao::class)->create([
            'tit_nome' => 'bachelor'
        ]);

        $this->assertInstanceOf(Titulacao::class, $this->repo->verifyTitulacao('bachelor'));
    }
}
