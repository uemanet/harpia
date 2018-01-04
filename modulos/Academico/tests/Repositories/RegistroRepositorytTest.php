<?php

use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Academico\Models\Livro;
use Modulos\Academico\Models\Registro;
use Modulos\Academico\Repositories\RegistroRepository;
use Stevebauman\EloquentTable\TableCollection;
use Tests\ModulosTestCase;
use Illuminate\Database\Eloquent\Collection;
use Modulos\Academico\Repositories\LivroRepository;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegistroRepositorytTest extends ModulosTestCase
{
    use DatabaseTransactions,
        WithoutMiddleware;

    protected $repo;

    protected $user;

    public function setUp()
    {
        parent::setUp();
        $this->repo = $this->app->make(RegistroRepository::class);
        $this->table = 'acd_registros';
        $this->user = factory(\Modulos\Seguranca\Models\Usuario::class)->create();
        $this->actingAs($this->user);
    }

    public function testCreate()
    {
        $data = factory(Registro::class)->create();
        $matricula = factory(Modulos\Academico\Models\Matricula::class)->create();
        $dipoma = factory(\Modulos\Academico\Models\Diploma::class)->raw([
            'dip_reg_id' => $data->reg_id,
            'dip_mat_id' => $matricula->mat_id,
        ]);

        $entry = $this->repo->create($data->toArray());

        $this->assertInstanceOf(Registro::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->toArray());
    }

//    public function testFind()
//    {
//        $entry = factory(Registro::class)->create();
//        $id = $entry->reg_id;
//        $fromRepository = $this->repo->find($id);
//
//        $this->assertInstanceOf(Registro::class, $fromRepository);
//        $this->assertDatabaseHas($this->table, $fromRepository->toArray());
//        $this->assertEquals($entry->toArray(), $fromRepository->toArray());
//    }
//
//    public function testUpdate()
//    {
//        $entry = factory(Registro::class)->create();
//        $id = $entry->reg_id;
//
//        $data = $entry->toArray();
//
//        $data['reg_folha'] = 2;
//
//        $return = $this->repo->update($data, $id);
//        $fromRepository = $this->repo->find($id);
//
//        $this->assertEquals(1, $return);
//        $this->assertDatabaseHas($this->table, $data);
//        $this->assertInstanceOf(Registro::class, $fromRepository);
//        $this->assertEquals($data, $fromRepository->toArray());
//    }
//
//    public function testDelete()
//    {
//        $entry = factory(Registro::class)->create();
//        $id = $entry->reg_id;
//
//        $return = $this->repo->delete($id);
//
//        $this->assertEquals(1, $return);
//        $this->assertDatabaseMissing($this->table, $entry->toArray());
//    }
//
//    public function testLists()
//    {
//        $entries = factory(Registro::class, 2)->create();
//
//        $model = new Registro();
//        $expected = $model->pluck('reg_registro', 'reg_id');
//        $fromRepository = $this->repo->lists('reg_id', 'reg_registro');
//
//        $this->assertEquals($expected, $fromRepository);
//    }
//
//    public function testSearch()
//    {
//        $entries = factory(Registro::class, 2)->create();
//
//        factory(Registro::class)->create([
//            'reg_registro' => 'search_registro'
//        ]);
//
//        $searchResult = $this->repo->search(array(['reg_registro', '=', 'search_registro']));
//
//        $this->assertInstanceOf(TableCollection::class, $searchResult);
//        $this->assertEquals(1, $searchResult->count());
//    }
//
//    public function testSearchWithSelect()
//    {
//        factory(Registro::class, 2)->create();
//
//        $entry = factory(Registro::class)->create([
//            'reg_registro' => "registro_to_find"
//        ]);
//
//        $expected = [
//            'reg_id' => $entry->reg_id,
//            'reg_registro' => $entry->reg_registro
//        ];
//
//        $searchResult = $this->repo->search(array(['reg_registro', '=', "registro_to_find"]), ['reg_id', 'reg_registro']);
//
//        $this->assertInstanceOf(TableCollection::class, $searchResult);
//        $this->assertEquals(1, $searchResult->count());
//        $this->assertEquals($expected, $searchResult->first()->toArray());
//    }
//
//    public function testAll()
//    {
//        // With empty database
//        $collection = $this->repo->all();
//
//        $this->assertEquals(0, $collection->count());
//
//        // Non-empty database
//        $created = factory(Registro::class, 10)->create();
//        $collection = $this->repo->all();
//
//        $this->assertEquals($created->count(), $collection->count());
//    }
//
//    public function testCount()
//    {
//        $created = factory(Registro::class, 10)->create();
//        $collection = $this->repo->all();
//
//        $this->assertEquals($created->count(), $this->repo->count());
//    }
//
//    public function testGetFillableModelFields()
//    {
//        $model = new Registro();
//        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
//    }
//
//    public function testPaginateWithoutParameters()
//    {
//        factory(Registro::class, 2)->create();
//
//        $response = $this->repo->paginate();
//
//        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
//        $this->assertGreaterThan(1, $response->total());
//    }
//
//    public function testPaginateWithSort()
//    {
//        factory(Registro::class, 2)->create();
//
//        $sort = [
//            'field' => 'reg_id',
//            'sort' => 'desc'
//        ];
//
//        $response = $this->repo->paginate($sort);
//
//        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
//        $this->assertEquals(2, $response->first()->reg_id);
//    }
//
//    public function testPaginateWithSearch()
//    {
//        factory(Registro::class, 2)->create();
//        factory(Registro::class)->create([
//            'reg_registro' => 'registro_to_search',
//        ]);
//
//        $search = [
//            [
//                'field' => 'reg_registro',
//                'type' => '=',
//                'term' => 'registro_to_search'
//            ]
//        ];
//
//        $response = $this->repo->paginate(null, $search);
//        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
//        $this->assertGreaterThan(0, $response->total());
//        $this->assertEquals('registro_to_search', $response->first()->reg_registro);
//    }
//
//    public function testPaginateRequest()
//    {
//        factory(Registro::class, 2)->create();
//
//        $requestParameters = [
//            'page' => '1',
//            'field' => 'reg_id',
//            'sort' => 'asc'
//        ];
//
//        $response = $this->repo->paginateRequest($requestParameters);
//        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
//        $this->assertGreaterThan(0, $response->total());
//    }
//
//    public function testFindByWithOptions()
//    {
//        factory(Modulos\Academico\Models\Registro::class)->create();
//
//        $result = $this->repo->findBy([
//            'reg_registro' => 1
//        ]);
//
//        $this->assertNotEmpty($result, '');
//    }
//
//    public function testFindByNoOptions()
//    {
//        factory(Modulos\Academico\Models\Registro::class)->create();
//
//        $result = $this->repo->findBy();
//
//        $this->assertNotEmpty($result, '');
//    }
}
