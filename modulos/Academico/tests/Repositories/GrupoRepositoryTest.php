<?php

use Tests\ModulosTestCase;
use Modulos\Academico\Models\Grupo;
use Stevebauman\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Academico\Repositories\GrupoRepository;

class GrupoRepositoryTest extends ModulosTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->repo = $this->app->make(GrupoRepository::class);
        $this->table = 'acd_grupos';
    }

    public function testCreate()
    {
        $data = factory(Grupo::class)->raw();
        $entry = $this->repo->create($data);

        $this->assertInstanceOf(Grupo::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->toArray());
    }

    public function testFind()
    {
        $entry = factory(Grupo::class)->create();
        $id = $entry->grp_id;
        $fromRepository = $this->repo->find($id);

        $this->assertInstanceOf(Grupo::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepository->toArray());
        $this->assertEquals($entry->toArray(), $fromRepository->toArray());
    }

    public function testUpdate()
    {
        $entry = factory(Grupo::class)->create();
        $id = $entry->grp_id;

        $data = $entry->toArray();

        $data['grp_nome'] = "slug";

        $return = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(Grupo::class, $fromRepository);
        $this->assertEquals($data, $fromRepository->toArray());
    }

    public function testDelete()
    {
        $entry = factory(Grupo::class)->create();
        $id = $entry->grp_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        $entries = factory(Grupo::class, 2)->create();

        $model = new Grupo();
        $expected = $model->pluck('grp_nome', 'grp_id');
        $fromRepository = $this->repo->lists('grp_id', 'grp_nome');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        $entries = factory(Grupo::class, 2)->create();

        factory(Grupo::class)->create([
            'grp_nome' => 'grupo'
        ]);

        $searchResult = $this->repo->search(array(['grp_nome', '=', 'grupo']));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(Grupo::class, 2)->create();

        $entry = factory(Grupo::class)->create([
            'grp_nome' => "grupo"
        ]);

        $expected = [
            'grp_id' => $entry->grp_id,
            'grp_nome' => $entry->grp_nome
        ];

        $searchResult = $this->repo->search(array(['grp_nome', '=', "grupo"]), ['grp_id', 'grp_nome']);

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
        $created = factory(Grupo::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(Grupo::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new Grupo();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Grupo::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Grupo::class, 2)->create();

        $sort = [
            'field' => 'grp_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->grp_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Grupo::class, 2)->create();
        factory(Grupo::class)->create([
            'grp_nome' => 'grupo',
        ]);

        $search = [
            [
                'field' => 'grp_nome',
                'type' => '=',
                'term' => 'grupo'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('grupo', $response->first()->grp_nome);
    }

    public function testPaginateRequest()
    {
        factory(Grupo::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'grp_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }
    public function testPaginateRequestByTurma()
    {
        $response = factory(\Modulos\Academico\Models\Grupo::class)->create();

        $response = $this->repo->paginateRequestByTurma($response->turma->trm_id);

        $this->assertNotEmpty($response, '');

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testPaginateRequestByTurmaWithParameters()
    {
        $response = factory(\Modulos\Academico\Models\Grupo::class)->create();

        $requestParameters['field'] = 'grp_nome';
        $requestParameters['sort'] = 'desc';
        $response = $this->repo->paginateRequestByTurma($response->turma->trm_id, $requestParameters);

        $this->assertNotEmpty($response, '');

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testListsAllById()
    {
        $response = factory(\Modulos\Academico\Models\Grupo::class)->create();

        $response = $this->repo->listsAllById($response->grp_id);

        $this->assertNotEmpty($response, '');
    }

    public function testFindAllByTurma()
    {
        $response = factory(\Modulos\Academico\Models\Grupo::class)->create();

        $response = $this->repo->findAllByTurma($response->turma->trm_id);

        $this->assertNotEmpty($response, '');
    }

    public function testFindAllByTurmaAndPolo()
    {
        $response = factory(\Modulos\Academico\Models\Grupo::class)->create();

        $response = $this->repo->getAllByTurmaAndPolo($response->turma->trm_id, $response->grp_pol_id);

        $this->assertNotEmpty($response, '');
    }

    public function testVerifyNameGrupo()
    {
        $response = factory(\Modulos\Academico\Models\Grupo::class)->create();

        $response = $this->repo->verifyNameGrupo('Grupo', $response->turma->trm_id);

        $this->assertEquals($response, false);
    }

    public function testVerifyNameGrupoReturnFalse()
    {
        $response = factory(\Modulos\Academico\Models\Grupo::class)->create(['grp_nome' =>  'Grupo']);

        $response = $this->repo->verifyNameGrupo('Grupo', $response->turma->trm_id, $response->grp_id);

        $this->assertEquals($response, false);
    }

    public function testVerifyNameGrupoReturnTrue()
    {
        $response = factory(\Modulos\Academico\Models\Grupo::class)->create(['grp_nome' =>  'Grupo']);

        $response = $this->repo->verifyNameGrupo('Grupo', $response->turma->trm_id, 10);

        $this->assertEquals($response, true);
    }

    public function testgetMovimentacoes()
    {
        $usuario = factory(\Modulos\Seguranca\Models\Usuario::class)->create();
        $grupo = factory(Grupo::class)->create();
        $tutoresgrupos = factory(\Modulos\Academico\Models\TutorGrupo::class, 3)->create(['ttg_grp_id' => $grupo->grp_id]);
        foreach ($tutoresgrupos as $tutorgrupo) {
            factory(\Modulos\Seguranca\Models\Auditoria::class)
                ->create(['log_usr_id' => $usuario->usr_id,
                          'log_table_id' => $tutorgrupo->ttg_grp_id,
                          'log_action' => 'INSERT',
                          'log_table' => 'acd_tutores_grupos'
                ]);
        }


        $response = $this->repo->getMovimentacoes($grupo->grp_id);

        $this->assertNotEmpty($response);
    }

    public function testgetMovimentacoesSemUsuarioDeLog()
    {
        $tutorgrupo = factory(\Modulos\Academico\Models\TutorGrupo::class)->create();

        $response = $this->repo->getMovimentacoes($tutorgrupo->ttg_grp_id);

        $this->assertCount(1, $response);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
