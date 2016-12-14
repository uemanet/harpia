<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modulos\Geral\Models\Titulacao;
use Modulos\Geral\Repositories\TitulacaoRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;

class TitulacaoRepositoryTest extends TestCase
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

        $this->repo = $this->app->make(TitulacaoRepository::class);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Titulacao::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Titulacao::class, 2)->create();

        $sort = [
            'field' => 'tit_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response[0]->tit_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Titulacao::class, 2)->create();

        factory(Titulacao::class)->create([
            'tit_nome' => 'graduacao',
        ]);

        $search = [
            [
                'field' => 'tit_nome',
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
        factory(Titulacao::class, 2)->create();

        $sort = [
            'field' => 'tit_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'tit_id',
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
        factory(Titulacao::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'tit_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testCreate()
    {
        $response = factory(Titulacao::class)->create();

        $data = $response->toArray();

        $this->assertInstanceOf(Titulacao::class, $response);

        $this->assertArrayHasKey('tit_id', $data);
    }

    public function testFind()
    {
        $data = factory(Titulacao::class)->create();

        $this->seeInDatabase('gra_titulacoes', $data->toArray());
    }

    public function testUpdate()
    {
        $data = factory(Titulacao::class)->create();

        $updateArray = $data->toArray();
        $updateArray['tit_nome'] = 'abcde_edcba';

        $titulacaoId = $updateArray['tit_id'];
        unset($updateArray['tit_id']);

        $response = $this->repo->update($updateArray, $titulacaoId, 'tit_id');

        $this->assertEquals(1, $response);
    }

    public function testDelete()
    {
        $data = factory(Titulacao::class)->create();
        $titulacaoId = $data->tit_id;

        $response = $this->repo->delete($titulacaoId);

        $this->assertEquals(1, $response);
    }

    public function testVerifyTitulacao()
    {
        $data = factory(Titulacao::class)->create();

        $titulacaoName = $data->tit_nome;

        $response = $this->repo->verifyTitulacao($titulacaoName);

        $this->assertNotEquals(null, $response);

    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
