<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modulos\Integracao\Repositories\ServicoRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;

class ServicoRepositoryTest extends TestCase
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

        $this->repo = $this->app->make(ServicoRepository::class);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Modulos\Integracao\Models\Servico::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Modulos\Integracao\Models\Servico::class, 2)->create();

        $sort = [
            'field' => 'ser_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertEquals(2, $response[0]->ser_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Modulos\Integracao\Models\Servico::class, 2)->create();

        factory(Modulos\Integracao\Models\Servico::class)->create([
            'ser_nome' => 'icatu',
        ]);

        $search = [
            [
                'field' => 'ser_nome',
                'type' => 'like',
                'term' => 'icatu'
            ]
        ];

        $response = $this->repo->paginate(null, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());

        $this->assertEquals('icatu', $response[0]->ser_nome);
    }

    public function testPaginateWithSearchAndOrder()
    {
        factory(Modulos\Integracao\Models\Servico::class, 2)->create();

        $sort = [
            'field' => 'ser_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'ser_id',
                'type' => '>',
                'term' => '1'
            ]
        ];

        $response = $this->repo->paginate($sort, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());

        $this->assertEquals(2, $response[0]->ser_id);
    }

    public function testPaginateRequest()
    {
        factory(Modulos\Integracao\Models\Servico::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'ser_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testCreate()
    {
        $response = factory(Modulos\Integracao\Models\Servico::class)->create();

        $this->assertInstanceOf(\Modulos\Integracao\Models\Servico::class, $response);

        $this->assertArrayHasKey('ser_id', $response->toArray());
    }

    public function testFind()
    {
        $data = factory(Modulos\Integracao\Models\Servico::class)->create();

        $this->assertDatabaseHas('int_servicos', $data->toArray());
    }

    public function testUpdate()
    {
        $data = factory(Modulos\Integracao\Models\Servico::class)->create();

        $updateArray = $data->toArray();
        $updateArray['ser_nome'] = 'abcde_edcba';

        $servicoId = $updateArray['ser_id'];
        unset($updateArray['ser_id']);

        $response = $this->repo->update($updateArray, $servicoId, 'ser_id');

        $this->assertEquals(1, $response);
    }

    public function testDelete()
    {
        $data = factory(Modulos\Integracao\Models\Servico::class)->create();
        $servicoId = $data->ser_id;

        $response = $this->repo->delete($servicoId);

        $this->assertEquals(1, $response);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
