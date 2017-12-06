<?php

use Tests\ModulosTestCase;
use Modulos\Seguranca\Models\Permissao;
use Stevebauman\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Seguranca\Repositories\PermissaoRepository;

class PermissaoRepositoryTest extends ModulosTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->repo = $this->app->make(PermissaoRepository::class);
        $this->table = 'seg_permissoes';
    }

    public function testCreate()
    {
        $data = factory(Permissao::class)->raw();
        $entry = $this->repo->create($data);

        $this->assertInstanceOf(Permissao::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->toArray());
    }

    public function testFind()
    {
        $entry = factory(Permissao::class)->create();
        $id = $entry->prm_id;
        $fromRepository = $this->repo->find($id);

        $this->assertInstanceOf(Permissao::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepository->toArray());
        $this->assertEquals($entry->toArray(), $fromRepository->toArray());
    }

    public function testUpdate()
    {
        $entry = factory(Permissao::class)->create();
        $id = $entry->prm_id;

        $data = $entry->toArray();

        $data['prm_nome'] = "permission";

        $return = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(Permissao::class, $fromRepository);
        $this->assertEquals($data, $fromRepository->toArray());
    }

    public function testDelete()
    {
        $entry = factory(Permissao::class)->create();
        $id = $entry->prm_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        $entries = factory(Permissao::class, 2)->create();

        $model = new Permissao();
        $expected = $model->pluck('prm_nome', 'prm_id');
        $fromRepository = $this->repo->lists('prm_id', 'prm_nome');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        $entries = factory(Permissao::class, 2)->create();

        factory(Permissao::class)->create([
            'prm_nome' => "permission"
        ]);

        $searchResult = $this->repo->search(array(['prm_nome', '=', "permission"]));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(Permissao::class, 2)->create();

        $entry = factory(Permissao::class)->create([
            'prm_nome' => "New name"
        ]);

        $expected = [
            'prm_id' => $entry->prm_id,
            'prm_nome' => $entry->prm_nome
        ];

        $searchResult = $this->repo->search(array(['prm_nome', '=', "New name"]), ['prm_id', 'prm_nome']);

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
        $created = factory(Permissao::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(Permissao::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new Permissao();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Permissao::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Permissao::class, 2)->create();

        $sort = [
            'field' => 'prm_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->prm_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Permissao::class, 2)->create();
        factory(Permissao::class)->create([
            'prm_nome' => 'permission',
        ]);

        $search = [
            [
                'field' => 'prm_nome',
                'type' => '=',
                'term' => 'permission'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('permission', $response->first()->prm_nome);
    }

    public function testPaginateRequest()
    {
        factory(Permissao::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'prm_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }
}
