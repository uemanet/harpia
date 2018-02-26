<?php

use Tests\ModulosTestCase;
use Modulos\Academico\Models\Curso;
use Modulos\Academico\Models\Vinculo;
use Modulos\Seguranca\Models\Usuario;
use Stevebauman\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Academico\Repositories\VinculoRepository;

class VinculoRepositoryTest extends ModulosTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->repo = $this->app->make(VinculoRepository::class);
        $this->table = 'acd_usuarios_cursos';
    }

    private function mockVinculo(int $qtdCursos = 1, $withBond = true, $curso = [])
    {
        // Usuario, Curso e Vinculo
        $user = factory(Usuario::class)->create();

        $cursos = factory(Curso::class, $qtdCursos)->create($curso);

        if ($withBond) {
            foreach ($cursos as $curso) {
                factory(Vinculo::class)->create([
                    'ucr_usr_id' => $user->usr_id,
                    'ucr_crs_id' => $curso->crs_id
                ]);
            }
        }

        return [$user, $cursos];
    }

    public function testCreate()
    {
        $data = factory(Vinculo::class)->raw();
        $entry = $this->repo->create($data);

        $this->assertInstanceOf(Vinculo::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->toArray());
    }

    public function testFind()
    {
        $entry = factory(Vinculo::class)->create();
        $id = $entry->ucr_id;
        $fromRepository = $this->repo->find($id);

        $this->assertInstanceOf(Vinculo::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepository->toArray());
        $this->assertEquals($entry->toArray(), $fromRepository->toArray());
    }

    public function testUpdate()
    {
        $entry = factory(Vinculo::class)->create();
        $id = $entry->ucr_id;

        $curso = factory(Curso::class)->create();
        $data = $entry->toArray();

        $data['ucr_crs_id'] = $curso->crs_id;

        $return = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(Vinculo::class, $fromRepository);
        $this->assertEquals($data, $fromRepository->toArray());
    }

    public function testDelete()
    {
        $entry = factory(Vinculo::class)->create();
        $id = $entry->ucr_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        $entries = factory(Vinculo::class, 2)->create();

        $model = new Vinculo();
        $expected = $model->pluck('ucr_usr_id', 'ucr_id');
        $fromRepository = $this->repo->lists('ucr_id', 'ucr_usr_id');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        $entries = factory(Vinculo::class, 2)->create();

        factory(Vinculo::class)->create([
            'ucr_usr_id' => 'centro'
        ]);

        $searchResult = $this->repo->search(array(['ucr_usr_id', '=', 'centro']));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        $users = factory(Usuario::class, 10)->create();

        factory(Vinculo::class, 2)->create([
            'ucr_usr_id' => factory(Usuario::class)->create()->usr_id,
            'ucr_crs_id' => factory(Curso::class)->create()->crs_id
        ]);

        $randomUser = $users->random();
        $id = $randomUser->usr_id;

        $entry = factory(Vinculo::class)->create([
            'ucr_usr_id' => $randomUser->usr_id,
            'ucr_crs_id' => factory(Curso::class)->create()->crs_id
        ]);

        $expected = [
            'ucr_id' => $entry->ucr_id,
            'ucr_usr_id' => $entry->ucr_usr_id
        ];

        $searchResult = $this->repo->search(array(['ucr_usr_id', '=', $id]), ['ucr_id', 'ucr_usr_id']);

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
        $created = factory(Vinculo::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(Vinculo::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new Vinculo();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Vinculo::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Vinculo::class, 2)->create();

        $sort = [
            'field' => 'ucr_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->ucr_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Vinculo::class, 2)->create();

        $user = factory(Usuario::class)->create();
        $id = $user->usr_id;

        factory(Vinculo::class)->create([
            'ucr_usr_id' => $id,
        ]);

        $search = [
            [
                'field' => 'ucr_usr_id',
                'type' => '=',
                'term' => $id
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals($id, $response->first()->ucr_usr_id);
    }

    public function testPaginateRequest()
    {
        factory(Vinculo::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'ucr_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->total());
        $this->assertEquals(2, $response->first()->ucr_id);
    }

    public function testGetCursos()
    {
        list($user, $cursos) = $this->mockVinculo(2);

        $id = $user->usr_id;
        $expected = $cursos->pluck('crs_id')->toArray();

        $fromRepository = $this->repo->getCursos($id);

        $this->assertEquals($expected, $fromRepository);
    }

    public function testPaginateCursosVinculados()
    {
        $firstUser = factory(Usuario::class)->create();

        list($user, $cursos) = $this->mockVinculo(5);

        $id = $user->usr_id;

        // Usuario sem vinculo
        $response = $this->repo->paginateCursosVinculados($firstUser->usr_id);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(0, $response->total());

        // Usuario com vinculo
        $response = $this->repo->paginateCursosVinculados($id);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(5, $response->total());
    }


    public function testGetCursosDisponiveis()
    {
        $firstUser = factory(Usuario::class)->create();

        list($user, $cursos) = $this->mockVinculo(2);
        $id = $user->usr_id;

        // Nenhum curso disponivel
        $response = $this->repo->getCursosDisponiveis($id);
        $this->assertEquals(0, $response->count());

        factory(Curso::class, 2)->create();
        $response = $this->repo->getCursosDisponiveis($id);
        $this->assertEquals(2, $response->count());
    }

    public function testUserHasVinculo()
    {
        // Curso sem vinculo
        $curso = factory(Curso::class)->create();

        list($user, $cursos) = $this->mockVinculo(2);
        $id = $user->usr_id;

        $this->assertFalse($this->repo->userHasVinculo($id, $curso->crs_id));
        $this->assertTrue($this->repo->userHasVinculo($id, $cursos[random_int(0, 1)]->crs_id));
    }

    public function testDeleteAllVinculosByCurso()
    {
        $curso = factory(Curso::class)->create();
        $users = factory(Usuario::class, 10)->create();

        foreach ($users as $user) {
            factory(Vinculo::class)->create([
                'ucr_usr_id' => $user->usr_id,
                'ucr_crs_id' => $curso->crs_id
            ]);
        }

        $curso = Curso::find($curso->crs_id);

        // Antes de remover
        $this->assertEquals(10, $curso->usuariosVinculados->count());

        // Remocao
        $result = $this->repo->deleteAllVinculosByCurso($curso->crs_id);

        $curso = Curso::find($curso->crs_id);

        // Remocao
        $this->assertEquals(0, $curso->usuariosVinculados->count());
    }
}
