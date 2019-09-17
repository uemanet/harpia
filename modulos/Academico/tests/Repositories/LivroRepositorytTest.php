<?php

use Tests\ModulosTestCase;
use Modulos\Academico\Models\Livro;
use Uemanet\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Academico\Repositories\LivroRepository;

class LivroRepositorytTest extends ModulosTestCase
{
    protected $repo;

    public function setUp(): void
    {
        parent::setUp();
        $this->repo = $this->app->make(LivroRepository::class);
        $this->table = 'acd_livros';
    }

    public function testCreate()
    {
        $data = factory(Livro::class)->raw();
        $entry = $this->repo->create($data);

        $this->assertInstanceOf(Livro::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->toArray());
    }

    public function testFind()
    {
        $entry = factory(Livro::class)->create();
        $id = $entry->liv_id;
        $fromRepository = $this->repo->find($id);

        $this->assertInstanceOf(Livro::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepository->toArray());
        $this->assertEquals($entry->toArray(), $fromRepository->toArray());
    }

    public function testUpdate()
    {
        $entry = factory(Livro::class)->create();
        $id = $entry->liv_id;

        $data = $entry->toArray();

        $data['liv_numero'] = 2;

        $return = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(Livro::class, $fromRepository);
        $this->assertEquals($data, $fromRepository->toArray());
    }

    public function testDelete()
    {
        $entry = factory(Livro::class)->create();
        $id = $entry->liv_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        factory(Livro::class, 2)->create();

        $model = new Livro();
        $expected = $model->pluck('liv_tipo_livro', 'liv_id');
        $fromRepository = $this->repo->lists('liv_id', 'liv_tipo_livro');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        factory(Livro::class, 2)->create([
            'liv_tipo_livro' => 'CERTIFICADO'
        ]);

        factory(Livro::class)->create([
            'liv_tipo_livro' => 'DIPLOMA'
        ]);

        $searchResult = $this->repo->search(array(['liv_tipo_livro', '=', 'DIPLOMA']));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(Livro::class, 2)->create();

        $entry = factory(Livro::class)->create([
            'liv_tipo_livro' => "CERTIFICADO"
        ]);

        $expected = [
            'liv_id' => $entry->liv_id,
            'liv_tipo_livro' => $entry->liv_tipo_livro
        ];

        $searchResult = $this->repo->search(array(['liv_tipo_livro', '=', "CERTIFICADO"]), ['liv_id', 'liv_tipo_livro']);

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
        $created = factory(Livro::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(Livro::class, 10)->create();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new Livro();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Livro::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Livro::class, 2)->create();

        $sort = [
            'field' => 'liv_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->liv_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Livro::class, 2)->create();
        factory(Livro::class)->create([
            'liv_tipo_livro' => 'CERTIFICADO',
        ]);

        $search = [
            [
                'field' => 'liv_tipo_livro',
                'type' => '=',
                'term' => 'CERTIFICADO'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('CERTIFICADO', $response->first()->liv_tipo_livro);
    }

    public function testPaginateRequest()
    {
        factory(Livro::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'liv_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }

    public function testFindByWithOptions()
    {
        factory(Modulos\Academico\Models\Livro::class)->create([
            'liv_tipo_livro' => 'CERTIFICADO'
        ]);

        $result = $this->repo->findBy([
            'liv_tipo_livro' => 'DIPLOMA'
        ]);

        $this->assertEquals(0, $result->count());

        factory(Modulos\Academico\Models\Livro::class)->create([
            'liv_tipo_livro' => 'DIPLOMA'
        ]);

        $result = $this->repo->findBy([
            'liv_tipo_livro' => 'DIPLOMA'
        ]);

        $this->assertEquals(1, $result->count());
    }

    public function testFindByNoOptions()
    {
        factory(Modulos\Academico\Models\Livro::class, 10)->create();

        $result = $this->repo->findBy();

        $this->assertEquals(10, $result->count());
    }
}
