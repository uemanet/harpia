<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modulos\Academico\Repositories\ModuloMatrizRepository;
use Modulos\Academico\Models\ModuloMatriz;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;

class ModuloMatrizRepositoryTest extends TestCase
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

        $this->repo = $this->app->make(ModuloMatrizRepository::class);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testPaginateWithoutParameters()
    {
        factory(\Modulos\Academico\Models\ModuloMatriz::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(\Modulos\Academico\Models\ModuloMatriz::class, 2)->create();

        $sort = [
            'field' => 'mdo_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response[0]->mdo_id);
    }

    public function testPaginateWithSearch()
    {
        factory(\Modulos\Academico\Models\ModuloMatriz::class, 2)->create();

        factory(\Modulos\Academico\Models\ModuloMatriz::class)->create([
            'mdo_nome' => 'Módulo 1',
        ]);

        $search = [
            [
                'field' => 'mdo_nome',
                'type' => 'like',
                'term' => 'Módulo 1'
            ]
        ];

        $response = $this->repo->paginate(null, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertCount(1, $response);
    }

    public function testPaginateWithSearchAndOrder()
    {
        factory(\Modulos\Academico\Models\ModuloMatriz::class, 2)->create();

        $sort = [
            'field' => 'mdo_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'mdo_id',
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
        factory(\Modulos\Academico\Models\ModuloMatriz::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'mdo_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testCreate()
    {
        $response = factory(\Modulos\Academico\Models\ModuloMatriz::class)->create();

        $data = $response->toArray();

        $this->assertInstanceOf(\Modulos\Academico\Models\ModuloMatriz::class, $response);

        $this->assertArrayHasKey('mdo_id', $data);
    }

    public function testFind()
    {
        $data = factory(\Modulos\Academico\Models\ModuloMatriz::class)->create();

        $this->seeInDatabase('acd_modulos_matrizes', $data->toArray());
    }

    public function testUpdate()
    {
        $data = factory(\Modulos\Academico\Models\ModuloMatriz::class)->create();

        $updateArray = $data->toArray();
        $updateArray['mdo_nome'] = 'abcde_edcba';

        $moduloId = $updateArray['mdo_id'];
        unset($updateArray['mdo_id']);

        $response = $this->repo->update($updateArray, $moduloId, 'mdo_id');

        $this->assertEquals(1, $response);
    }

    public function testVerifyNameMatriz()
    {
        $ModuloMatriz = factory(Modulos\Academico\Models\ModuloMatriz::class)->create();
        $moduloMatrizRepository = new ModuloMatrizRepository(new ModuloMatriz());
        $retorno = $moduloMatrizRepository->verifyNameMatriz($ModuloMatriz->mdo_nome, $ModuloMatriz->matriz->mtc_id);

        $this->assertEquals(true, $retorno);
    }

    public function testGetAllModulosByMatriz()
    {
        $ModuloMatriz = factory(Modulos\Academico\Models\ModuloMatriz::class)->create();
        $moduloMatrizRepository = new ModuloMatrizRepository(new ModuloMatriz());
        $retorno = $moduloMatrizRepository->getAllModulosByMatriz($ModuloMatriz->matriz->mtc_id);


        $this->assertNotEmpty($retorno);
    }

    public function testDelete()
    {
        $data = factory(\Modulos\Academico\Models\ModuloMatriz::class)->create();
        $moduloId = $data->mdo_id;

        $response = $this->repo->delete($moduloId);

        $this->assertEquals(1, $response);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
