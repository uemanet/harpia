<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modulos\Academico\Repositories\VinculoRepository;
use Modulos\Academico\Models\Vinculo;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;

class VinculoRepositoryTest extends TestCase
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

    public function login()
    {
        $user = factory(Modulos\Seguranca\Models\Usuario::class)->create();

        $this->actingAs($user);
    }


    public function setUp()
    {
        parent::setUp();

        Artisan::call('modulos:migrate');

        $this->repo = $this->app->make(VinculoRepository::class);
        $this->login();
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
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
            'field' => 'ucr_crs_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response[0]->ucr_crs_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Vinculo::class, 2)->create();

        factory(Vinculo::class)->create([
            'ucr_crs_id' => 3,
        ]);

        $search = [
            [
                'field' => 'ucr_crs_id',
                'type' => '=',
                'term' => 1
            ]
        ];

        $response = $this->repo->paginate(null, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertCount(1, $response);
    }

    public function testPaginateWithSearchAndOrder()
    {
        factory(Vinculo::class, 2)->create();

        $sort = [
            'field' => 'ucr_crs_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'ucr_crs_id',
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
        factory(Vinculo::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'mtc_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testCreate()
    {
        $response = factory(Vinculo::class)->create();

        $data = $response->toArray();

        $this->assertInstanceOf(Vinculo::class, $response);

        $this->assertArrayHasKey('ucr_crs_id', $data);
    }

    public function testFind()
    {
        $data = factory(Vinculo::class)->create();

        $this->seeInDatabase('acd_usuarios_cursos', $data->toArray());
    }

    public function testUpdate()
    {
        $data = factory(Vinculo::class)->create();

        $updateArray = $data->toArray();
        $updateArray['ucr_crs_id'] = 2;

        $vinculo = $updateArray['ucr_id'];
        unset($updateArray['ucr_id']);

        $response = $this->repo->update($updateArray, $vinculo, 'ucr_id');

        $this->assertEquals(1, $response);
    }

    public function testDelete()
    {
        $data = factory(Vinculo::class)->create();
        $vinculo = $data->ucr_id;

        $response = $this->repo->delete($vinculo);

        $this->assertEquals(1, $response);
    }

    public function testPaginateCursosVinculados()
    {
        factory(\Modulos\Academico\Models\Vinculo::class, 2)->create();

        $response = $this->repo->paginateCursosVinculados(Auth::user()->usr_id);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testGetCursos()
    {
        factory(\Modulos\Academico\Models\Vinculo::class, 2)->create();

        $response = $this->repo->getCursos(Auth::user()->usr_id);

        $this->assertGreaterThan(0, $response);
    }


    public function testGetCursosDisponiveis()
    {
        factory(\Modulos\Academico\Models\Vinculo::class, 2)->create();

        factory(\Modulos\Academico\Models\Curso::class, 2)->create();

        $response = $this->repo->getCursosDisponiveis(Auth::user()->usr_id);

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $response);

        $this->arrayHasKey('crs_nome', $response->all());

        $this->assertGreaterThan(1, $response->all());
    }

    public function userHasVinculo()
    {
        factory(\Modulos\Academico\Models\Vinculo::class, 2)->create();

        $curso = factory(\Modulos\Academico\Models\Curso::class)->create();

        $query = $this->repo->userHasVinculo(Auth::user()->usr_id, 1);

        $this->assertTrue($query);

        $query = $this->repo->userHasVinculo(Auth::user()->usr_id, $curso->crs_id);

        $this->assertFalse($query);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
