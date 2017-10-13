<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modulos\Seguranca\Repositories\PermissaoRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;

class PermissaoRepositoryTest extends TestCase
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

        $this->repo = $this->app->make(PermissaoRepository::class);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Modulos\Seguranca\Models\Permissao::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Modulos\Seguranca\Models\Permissao::class, 2)->create();

        $sort = [
            'field' => 'prm_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response[0]->prm_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Modulos\Seguranca\Models\Permissao::class, 2)->create();

        factory(Modulos\Seguranca\Models\Permissao::class)->create([
            'prm_nome' => 'seguranca',
        ]);

        $search = [
            [
                'field' => 'prm_nome',
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
        factory(Modulos\Seguranca\Models\Permissao::class, 2)->create();

        $sort = [
            'field' => 'prm_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'prm_id',
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
        factory(Modulos\Seguranca\Models\Permissao::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'prm_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testCreate()
    {
        $response = factory(Modulos\Seguranca\Models\Permissao::class)->create();

        $data = $response->toArray();

        $this->assertInstanceOf(\Modulos\Seguranca\Models\Permissao::class, $response);

        $this->assertArrayHasKey('prm_id', $data);
    }

    public function testFind()
    {
        $data = factory(Modulos\Seguranca\Models\Permissao::class)->create();

        $this->assertDatabaseHas('seg_permissoes', $data->toArray());
    }

    public function testUpdate()
    {
        $data = factory(Modulos\Seguranca\Models\Permissao::class)->create();

        $updateArray = $data->toArray();
        $updateArray['prm_nome'] = 'abcde_edcba';

        $moduloId = $updateArray['prm_id'];
        unset($updateArray['prm_id']);

        $response = $this->repo->update($updateArray, $moduloId, 'prm_id');

        $this->assertEquals(1, $response);
    }

    public function testDelete()
    {
        $data = factory(Modulos\Seguranca\Models\Permissao::class)->create();
        $moduloId = $data->prm_id;

        $response = $this->repo->delete($moduloId);

        $this->assertEquals(1, $response);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
