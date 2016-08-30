<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modulos\Geral\Repositories\AnexoRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;
use Modulos\Geral\Models\Anexo;

class AnexoRepositoryTest extends TestCase
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

        $this->repo = $this->app->make(AnexoRepository::class);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Anexo::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Anexo::class, 2)->create();

        $sort = [
            'field' => 'anx_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response[0]->anx_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Anexo::class, 2)->create();

        factory(Anexo::class)->create([
            'anx_nome' => 'arquivo',
        ]);

        $search = [
            [
                'field' => 'anx_nome',
                'type' => 'like',
                'term' => 'arquivo'
            ]
        ];

        $response = $this->repo->paginate(null, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertCount(1, $response);
    }

    public function testPaginateWithSearchAndOrder()
    {
        factory(Anexo::class, 2)->create();

        $sort = [
            'field' => 'anx_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'anx_id',
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
        factory(Anexo::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'anx_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testCreate()
    {
        $response = factory(Anexo::class)->create();

        $data = $response->toArray();

        $this->assertInstanceOf(Anexo::class, $response);

        $this->assertArrayHasKey('anx_id', $data);
    }

    public function testFind()
    {
        $data = factory(Anexo::class)->create();

        $this->seeInDatabase('gra_anexos', $data->toArray());
    }

    public function testUpdate()
    {
        $data = factory(Anexo::class)->create();

        $updateArray = $data->toArray();
        $updateArray['anx_nome'] = 'abcde_edcba';

        $pessoaId = $updateArray['anx_id'];
        unset($updateArray['anx_id']);

        $response = $this->repo->update($updateArray, $pessoaId, 'anx_id');

        $this->assertEquals(1, $response);
    }

    public function testDelete()
    {
        $data = factory(Anexo::class)->create();
        $pessoaId = $data->anx_id;

        $response = $this->repo->delete($pessoaId);

        $this->assertEquals(1, $response);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
