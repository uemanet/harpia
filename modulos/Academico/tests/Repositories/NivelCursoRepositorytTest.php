<?php

use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Academico\Models\Livro;
use Modulos\Academico\Models\NivelCurso;
use Modulos\Academico\Repositories\NivelCursoRepository;
use Stevebauman\EloquentTable\TableCollection;
use Tests\ModulosTestCase;
use Illuminate\Database\Eloquent\Collection;
use Modulos\Academico\Repositories\LivroRepository;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NivelCursoRepositorytTest extends ModulosTestCase
{
    use DatabaseTransactions,
        WithoutMiddleware;

    protected $repo;
    protected $docrepo;

    public function setUp()
    {
        parent::setUp();
        $this->repo = $this->app->make(NivelCursoRepository::class);
        $this->table = 'acd_niveis_cursos';
    }

    public function testCreate()
    {
        $data = factory(NivelCurso::class)->raw();
        $entry = $this->repo->create($data);

        $this->assertInstanceOf(NivelCurso::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->toArray());
    }

    public function testFind()
    {
        $entry = factory(NivelCurso::class)->create();
        $id = $entry->nvc_id;
        $fromRepository = $this->repo->find($id);

        $this->assertInstanceOf(NivelCurso::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepository->toArray());
        $this->assertEquals($entry->toArray(), $fromRepository->toArray());
    }

    public function testUpdate()
    {
        $entry = factory(NivelCurso::class)->create();
        $id = $entry->nvc_id;

        $data = $entry->toArray();

        $data['nvc_nome'] = 'Nome Nivel';

        $return = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(NivelCurso::class, $fromRepository);
        $this->assertEquals($data, $fromRepository->toArray());
    }

    public function testDelete()
    {
        $entry = factory(NivelCurso::class)->create();
        $id = $entry->nvc_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        $entries = factory(NivelCurso::class, 2)->create();

        $model = new NivelCurso();
        $expected = $model->pluck('nvc_nome', 'nvc_id');
        $fromRepository = $this->repo->lists('nvc_id', 'nvc_nome');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        $entries = factory(NivelCurso::class, 2)->create();

        factory(NivelCurso::class)->create([
            'nvc_nome' => 'search_nvc_nome'
        ]);

        $searchResult = $this->repo->search(array(['nvc_nome', '=', 'search_nvc_nome']));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(NivelCurso::class, 2)->create();

        $entry = factory(NivelCurso::class)->create([
            'nvc_nome' => "nvc_nome_to_find"
        ]);

        $expected = [
            'nvc_id' => $entry->nvc_id,
            'nvc_nome' => $entry->nvc_nome
        ];

        $searchResult = $this->repo->search(array(['nvc_nome', '=', "nvc_nome_to_find"]), ['nvc_id', 'nvc_nome']);

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
        $created = factory(NivelCurso::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(NivelCurso::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new NivelCurso();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(NivelCurso::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(NivelCurso::class, 2)->create();

        $sort = [
            'field' => 'nvc_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->nvc_id);
    }

    public function testPaginateWithSearch()
    {
        factory(NivelCurso::class, 2)->create();
        factory(NivelCurso::class)->create([
            'nvc_nome' => 'nvc_nome_to_search',
        ]);

        $search = [
            [
                'field' => 'nvc_nome',
                'type' => '=',
                'term' => 'nvc_nome_to_search'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('nvc_nome_to_search', $response->first()->nvc_nome);
    }

    public function testPaginateRequest()
    {
        factory(NivelCurso::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'nvc_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }
}
