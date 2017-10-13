<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modulos\Geral\Repositories\PessoaRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

class PessoaRepositoryTest extends TestCase
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

        $this->repo = $this->app->make(PessoaRepository::class);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Modulos\Geral\Models\Pessoa::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Modulos\Geral\Models\Pessoa::class, 2)->create();

        $sort = [
            'field' => 'pes_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response[0]->pes_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Modulos\Geral\Models\Pessoa::class, 2)->create();

        factory(Modulos\Geral\Models\Pessoa::class)->create([
            'pes_nome' => 'seguranca',
        ]);

        $search = [
            [
                'field' => 'pes_nome',
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
        factory(Modulos\Geral\Models\Pessoa::class, 2)->create();

        $sort = [
            'field' => 'pes_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'pes_id',
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
        factory(Modulos\Geral\Models\Pessoa::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'pes_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testCreate()
    {
        $response = factory(Modulos\Geral\Models\Pessoa::class)->create();

        $data = $response->toArray();

        $this->assertInstanceOf(\Modulos\Geral\Models\Pessoa::class, $response);

        $this->assertArrayHasKey('pes_id', $data);
    }

    public function testFind()
    {
        $dados = factory(Modulos\Geral\Models\Pessoa::class)->create();

        $data = $dados->toArray();
        // Retorna para date format americano antes de comparar com o banco
        $data['pes_nascimento'] = Carbon::createFromFormat('d/m/Y', $data['pes_nascimento'])->toDateString();
        unset($data['pes_estado_civil']);

        $this->assertDatabaseHas('gra_pessoas', $data);
    }

    public function testUpdate()
    {
        $data = factory(Modulos\Geral\Models\Pessoa::class)->create();

        $updateArray = $data->toArray();
        $updateArray['pes_nome'] = 'abcde_edcba';

        $pessoaId = $updateArray['pes_id'];
        unset($updateArray['pes_id']);

        $response = $this->repo->update($updateArray, $pessoaId, 'pes_id');

        $this->assertEquals(1, $response);
    }

    public function testDelete()
    {
        $data = factory(Modulos\Geral\Models\Pessoa::class)->create();
        $pessoaId = $data->pes_id;

        $response = $this->repo->delete($pessoaId);

        $this->assertEquals(1, $response);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
