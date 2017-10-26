<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modulos\Academico\Repositories\GrupoRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;

class GrupoRepositoryTest extends TestCase
{
    use DatabaseTransactions,
        WithoutMiddleware;

    protected $repo;

    public function createApplication()
    {
        putenv('DB_CONNECTION=sqlite_testing');

        $app = require __DIR__ . '/../../../../bootstrap/app.php';

        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

        return $app;
    }

    public function setUp()
    {
        parent::setUp();

        Artisan::call('modulos:migrate');

        $this->repo = $this->app->make(GrupoRepository::class);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testPaginateWithoutParameters()
    {
        factory(\Modulos\Academico\Models\Grupo::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(\Modulos\Academico\Models\Grupo::class, 2)->create();

        $sort = [
            'field' => 'grp_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response[0]->grp_id);
    }

    public function testPaginateWithSearch()
    {
        factory(\Modulos\Academico\Models\Grupo::class, 2)->create();

        factory(\Modulos\Academico\Models\Grupo::class)->create([
            'grp_nome' => 'São Luís 1',
        ]);

        $search = [
            [
                'field' => 'grp_nome',
                'type' => 'like',
                'term' => 'São Luís 1'
            ]
        ];

        $response = $this->repo->paginate(null, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertCount(1, $response);
    }

    public function testPaginateWithSearchAndOrder()
    {
        factory(\Modulos\Academico\Models\Grupo::class, 2)->create();

        $sort = [
            'field' => 'grp_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'grp_id',
                'type' => '>',
                'term' => '1'
            ]
        ];

        $response = $this->repo->paginate($sort, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testPaginateRequest()
    {
        factory(\Modulos\Academico\Models\Grupo::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'grp_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testCreate()
    {
        $response = factory(\Modulos\Academico\Models\Grupo::class)->create();

        $data = $response->toArray();

        $this->assertInstanceOf(\Modulos\Academico\Models\Grupo::class, $response);

        $this->assertArrayHasKey('grp_id', $data);
    }

    public function testFind()
    {
        $data = factory(\Modulos\Academico\Models\Grupo::class)->create();

        $this->assertDatabaseHas('acd_grupos', $data->toArray());
    }

    public function testUpdate()
    {
        $data = factory(\Modulos\Academico\Models\Grupo::class)->create();

        $updateArray = $data->toArray();
        $updateArray['grp_nome'] = 'abcde_edcba';

        $grupoId = $updateArray['grp_id'];
        unset($updateArray['grp_id']);

        $response = $this->repo->update($updateArray, $grupoId, 'grp_id');

        $this->assertEquals(1, $response);
    }

    public function testDelete()
    {
        $data = factory(\Modulos\Academico\Models\Grupo::class)->create();
        $grupoId = $data->grp_id;

        $response = $this->repo->delete($grupoId);

        $this->assertEquals(1, $response);
    }

    public function testPaginateRequestByTurma()
    {
        $response = factory(\Modulos\Academico\Models\Grupo::class)->create();

        $response = $this->repo->paginateRequestByTurma($response->turma->trm_id);

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

        $response = $this->repo->listsAllById($response->turma->trm_id);

        $this->assertNotEmpty($response, '');
    }

    public function testVerifyNameGrupo()
    {
        $response = factory(\Modulos\Academico\Models\Grupo::class)->create();

        $response = $this->repo->verifyNameGrupo('Grupo', $response->turma->trm_id);

        $this->assertEquals($response, false);
    }


    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
