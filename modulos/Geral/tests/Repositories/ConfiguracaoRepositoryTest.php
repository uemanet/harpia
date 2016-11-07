<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modulos\Geral\Repositories\ConfiguracaoRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;
use Modulos\Geral\Models\Configuracao;

class ConfiguracaoRepositoryTest extends TestCase
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

        $this->repo = $this->app->make(ConfiguracaoRepository::class);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Configuracao::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Configuracao::class, 2)->create();

        $sort = [
            'field' => 'cnf_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response[0]->cnf_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Configuracao::class, 2)->create();

        factory(Configuracao::class)->create([
            'cnf_nome' => 'arquivo',
        ]);

        $search = [
            [
                'field' => 'cnf_nome',
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
        factory(Configuracao::class, 2)->create();

        $sort = [
            'field' => 'cnf_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'cnf_id',
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
        factory(Configuracao::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'cnf_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testCreate()
    {
        $response = factory(Configuracao::class)->create();

        $data = $response->toArray();

        $this->assertInstanceOf(Configuracao::class, $response);

        $this->assertArrayHasKey('cnf_id', $data);
    }

    public function testFind()
    {
        $data = factory(Configuracao::class)->create();

        $this->seeInDatabase('gra_configuracoes', $data->toArray());
    }

    public function testUpdate()
    {
        $data = factory(Configuracao::class)->create();

        $updateArray = $data->toArray();
        $updateArray['cnf_nome'] = 'abcde_edcba';

        $pessoaId = $updateArray['cnf_id'];
        unset($updateArray['cnf_id']);

        $response = $this->repo->update($updateArray, $pessoaId, 'cnf_id');

        $this->assertEquals(1, $response);
    }

    public function testDelete()
    {
        $data = factory(Configuracao::class)->create();
        $pessoaId = $data->cnf_id;

        $response = $this->repo->delete($pessoaId);

        $this->assertEquals(1, $response);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
