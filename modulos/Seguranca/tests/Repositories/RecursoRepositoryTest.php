<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modulos\Seguranca\Repositories\RecursoRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;

class RecursoRepositoryTest extends TestCase
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

        $this->repo = $this->app->make(RecursoRepository::class);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Modulos\Seguranca\Models\Recurso::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Modulos\Seguranca\Models\Recurso::class, 2)->create();

        $sort = [
            'field' => 'rcs_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response[0]->rcs_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Modulos\Seguranca\Models\Recurso::class, 2)->create();

        factory(Modulos\Seguranca\Models\Recurso::class)->create([
            'rcs_nome' => 'seguranca',
        ]);

        $search = [
            [
                'field' => 'rcs_nome',
                'type' => 'like',
                'term' => 'seguranca'
            ]
        ];

        $response = $this->repo->paginate(null, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertCount(1, $response);
    }

    public function testPaginateWithSearchAndOrder()
    {
        factory(Modulos\Seguranca\Models\Recurso::class, 2)->create();

        $sort = [
            'field' => 'rcs_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'rcs_id',
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
        factory(Modulos\Seguranca\Models\Recurso::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'rcs_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testCreate()
    {
        $response = factory(Modulos\Seguranca\Models\Recurso::class)->create();

        $data = $response->toArray();

        $this->assertInstanceOf(\Modulos\Seguranca\Models\Recurso::class, $response);

        $this->assertArrayHasKey('rcs_id', $data);
    }

    public function testFind()
    {
        $data = factory(Modulos\Seguranca\Models\Recurso::class)->create();

        $this->seeInDatabase('seg_recursos', $data->toArray());
    }

    public function testUpdate()
    {
        $data = factory(Modulos\Seguranca\Models\Recurso::class)->create();

        $updateArray = $data->toArray();
        $updateArray['rcs_nome'] = 'abcde_edcba';

        $moduloId = $updateArray['rcs_id'];
        unset($updateArray['rcs_id']);

        $response = $this->repo->update($updateArray, $moduloId, 'rcs_id');

        $this->assertEquals(1, $response);
    }

    public function testDelete()
    {
        $data = factory(Modulos\Seguranca\Models\Recurso::class)->create();
        $moduloId = $data->rcs_id;

        $response = $this->repo->delete($moduloId);

        $this->assertEquals(1, $response);
    }

    public function testFindAllByModulo()
    {
        factory(Modulos\Seguranca\Models\Modulo::class)->create();
        factory(Modulos\Seguranca\Models\CategoriaRecurso::class)->create();
        factory(Modulos\Seguranca\Models\Recurso::class)->create();

        $moduloId = 1;

        $response = $this->repo->findAllByModulo($moduloId);

        $dataObject = $response[0];

        $this->assertNotEmpty($dataObject->rcs_id);
        $this->assertNotEmpty($dataObject->rcs_nome);
    }

    public function testListsAllByModulo()
    {
        factory(Modulos\Seguranca\Models\Modulo::class)->create();
        factory(Modulos\Seguranca\Models\CategoriaRecurso::class)->create();
        factory(Modulos\Seguranca\Models\Recurso::class)->create();

        $moduloId = 1;

        $response = $this->repo->listsAllByModulo($moduloId);

        $dataObject = current($response);

        $this->assertNotEmpty($dataObject);
        $this->assertContainsOnly('string', $response);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
