<?php

use Tests\ModulosTestCase;
use Modulos\Academico\Models\TutorGrupo;
use Stevebauman\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Academico\Repositories\TutorGrupoRepository;

class TutorGrupoRepositoryTest extends ModulosTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->repo = $this->app->make(TutorGrupoRepository::class);
        $this->table = 'acd_tutores_grupos';
    }

    public function testCreate()
    {
        $data = factory(TutorGrupo::class)->raw();
        $entry = $this->repo->create($data);

        $this->assertInstanceOf(TutorGrupo::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->getOriginal());
    }

    public function testFind()
    {
        $entry = factory(TutorGrupo::class)->create();
        $id = $entry->ttg_id;
        $fromRepository = $this->repo->find($id);

        $entryData = $entry->toArray();
        $entryData['ttg_data_inicio'] = $entry->getOriginal('ttg_data_inicio');
        $entryData['ttg_tipo_tutoria'] = $entry->getOriginal('ttg_tipo_tutoria');

        $this->assertInstanceOf(TutorGrupo::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepository->getOriginal());

        $this->assertEquals($entryData, $fromRepository->getOriginal());
    }

    public function testUpdate()
    {
        $entry = factory(TutorGrupo::class)->create([
            'ttg_tipo_tutoria' => "distancia"
        ]);

        $id = $entry->ttg_id;

        $data = $entry->toArray();

        $data['ttg_tipo_tutoria'] = "presencial";

        $return = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);
        $data['ttg_data_inicio'] = $entry->getOriginal('ttg_data_inicio');
        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(TutorGrupo::class, $fromRepository);
        $this->assertEquals($data, $fromRepository->getOriginal());
    }

    public function testDelete()
    {
        $entry = factory(TutorGrupo::class)->create();
        $id = $entry->ttg_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        factory(TutorGrupo::class, 2)->create();

        $model = new TutorGrupo();
        $expected = $model->pluck('ttg_tipo_tutoria', 'ttg_id');
        $fromRepository = $this->repo->lists('ttg_id', 'ttg_tipo_tutoria');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        factory(TutorGrupo::class, 2)->create([
            'ttg_tipo_tutoria' => 'distancia'
        ]);

        factory(TutorGrupo::class)->create([
            'ttg_tipo_tutoria' => 'presencial'
        ]);

        $searchResult = $this->repo->search(array(['ttg_tipo_tutoria', '=', 'presencial']));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(TutorGrupo::class, 2)->create([
            'ttg_tipo_tutoria' => 'distancia'
        ]);

        $entry = factory(TutorGrupo::class)->create([
            'ttg_tipo_tutoria' => 'presencial'
        ]);

        $expected = [
            'ttg_id' => $entry->ttg_id,
            'ttg_tipo_tutoria' => $entry->ttg_tipo_tutoria
        ];

        $searchResult = $this->repo->search(array(['ttg_tipo_tutoria', '=', "presencial"]), ['ttg_id', 'ttg_tipo_tutoria']);

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
        $created = factory(TutorGrupo::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(TutorGrupo::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new TutorGrupo();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(TutorGrupo::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(TutorGrupo::class, 2)->create();

        $sort = [
            'field' => 'ttg_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->ttg_id);
    }

    public function testPaginateWithSearch()
    {
        factory(TutorGrupo::class, 2)->create([
            'ttg_tipo_tutoria' => 'distancia'
        ]);

        factory(TutorGrupo::class)->create([
            'ttg_tipo_tutoria' => 'presencial'
        ]);

        $search = [
            [
                'field' => 'ttg_tipo_tutoria',
                'type' => '=',
                'term' => 'presencial'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('Presencial', $response->first()->ttg_tipo_tutoria);
    }

    public function testPaginateRequest()
    {
        factory(TutorGrupo::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'ttg_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }

    public function testgetTiposTutoria()
    {
        $tutorgrupo = factory(\Modulos\Academico\Models\TutorGrupo::class)->create();
        $response = $this->repo->getTiposTutoria($tutorgrupo->grupo->grp_id);

        $this->assertNotEmpty($response);
    }

    public function testpaginateRequestByGrupo()
    {
        $tutorgrupo = factory(\Modulos\Academico\Models\TutorGrupo::class)->create();
        $response = $this->repo->paginateRequestByGrupo($tutorgrupo->grupo->grp_id);

        $this->assertNotEmpty($response);
    }

    public function testpaginateRequestByGrupoWithParameters()
    {
        $tutorgrupo = factory(\Modulos\Academico\Models\TutorGrupo::class)->create();
        $requestParameters['field'] = 'ttg_tut_id';
        $requestParameters['sort'] = 'desc';

        $response = $this->repo->paginateRequestByGrupo($tutorgrupo->grupo->grp_id, $requestParameters);

        $this->assertNotEmpty($response);
    }

    public function tearDown(): void
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
