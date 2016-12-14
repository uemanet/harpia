<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modulos\Geral\Models\TitulacaoInformacao;
use Modulos\Geral\Repositories\TitulacaoInformacaoRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;

class TitulacaoInformacaoRepositoryTest extends TestCase
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

        $this->repo = $this->app->make(TitulacaoInformacaoRepository::class);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testPaginateWithoutParameters()
    {
        factory(TitulacaoInformacao::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(TitulacaoInformacao::class, 2)->create();

        $sort = [
            'field' => 'tin_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response[0]->tin_id);
    }

    public function testPaginateWithSearch()
    {
        factory(TitulacaoInformacao::class, 2)->create();

        factory(TitulacaoInformacao::class)->create([
            'tin_titulo' => 'graduacao',
        ]);

        $search = [
            [
                'field' => 'tin_titulo',
                'type' => 'like',
                'term' => 'graduacao'
            ]
        ];

        $response = $this->repo->paginate(null, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertCount(1, $response);
    }

    public function testPaginateWithSearchAndOrder()
    {
        factory(TitulacaoInformacao::class, 2)->create();

        $sort = [
            'field' => 'tin_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'tin_id',
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
        factory(TitulacaoInformacao::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'tin_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testCreate()
    {
        $response = factory(TitulacaoInformacao::class)->create();

        $data = $response->toArray();

        $this->assertInstanceOf(TitulacaoInformacao::class, $response);

        $this->assertArrayHasKey('tin_id', $data);
    }

    public function testFind()
    {
        $data = factory(TitulacaoInformacao::class)->create();

        $this->seeInDatabase('gra_titulacoes_informacoes', $data->toArray());
    }

    public function testUpdate()
    {
        $data = factory(TitulacaoInformacao::class)->create();

        $updateArray = $data->toArray();
        $updateArray['tin_titulo'] = 'abcde_edcba';

        $titulacaoId = $updateArray['tin_id'];
        unset($updateArray['tin_id']);

        $response = $this->repo->update($updateArray, $titulacaoId, 'tin_id');

        $this->assertEquals(1, $response);
    }

    public function testDelete()
    {
        $data = factory(TitulacaoInformacao::class)->create();
        $titulacaoId = $data->tin_id;

        $response = $this->repo->delete($titulacaoId);

        $this->assertEquals(1, $response);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
