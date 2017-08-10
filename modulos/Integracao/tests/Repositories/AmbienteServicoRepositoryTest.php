<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modulos\Integracao\Repositories\AmbienteServicoRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;

class AmbienteServicoRepositoryTest extends TestCase
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

        $this->repo = $this->app->make(AmbienteServicoRepository::class);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Modulos\Integracao\Models\AmbienteServico::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Modulos\Integracao\Models\AmbienteServico::class, 2)->create();

        $sort = [
            'field' => 'asr_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertEquals(2, $response[0]->asr_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Modulos\Integracao\Models\AmbienteServico::class, 2)->create();

        factory(Modulos\Integracao\Models\AmbienteServico::class)->create([
            'asr_token' => 'icatu',
        ]);

        $search = [
            [
                'field' => 'asr_token',
                'type' => 'like',
                'term' => 'icatu'
            ]
        ];

        $response = $this->repo->paginate(null, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());

        $this->assertEquals('icatu', $response[0]->asr_token);
    }

    public function testPaginateWithSearchAndOrder()
    {
        factory(Modulos\Integracao\Models\AmbienteServico::class, 2)->create();

        $sort = [
            'field' => 'asr_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'asr_id',
                'type' => '>',
                'term' => '1'
            ]
        ];

        $response = $this->repo->paginate($sort, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());

        $this->assertEquals(2, $response[0]->asr_id);
    }

    public function testPaginateRequest()
    {
        factory(Modulos\Integracao\Models\AmbienteServico::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'asr_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testCreate()
    {
        $response = factory(Modulos\Integracao\Models\AmbienteServico::class)->create();

        $this->assertInstanceOf(\Modulos\Integracao\Models\AmbienteServico::class, $response);

        $this->assertArrayHasKey('asr_id', $response->toArray());
    }

    public function testFind()
    {
        $data = factory(Modulos\Integracao\Models\AmbienteServico::class)->create();

        $this->seeInDatabase('int_ambientes_servicos', $data->toArray());
    }

    public function testUpdate()
    {
        $data = factory(Modulos\Integracao\Models\AmbienteServico::class)->create();

        $updateArray = $data->toArray();
        $updateArray['asr_token'] = 'asd5weAse78r54asskhae';

        $ambientevirtualdId = $updateArray['asr_id'];
        unset($updateArray['asr_id']);

        $response = $this->repo->update($updateArray, $ambientevirtualdId, 'asr_id');

        $this->assertEquals(1, $response);
    }

    public function testDelete()
    {
        $data = factory(Modulos\Integracao\Models\AmbienteServico::class)->create();
        $ambientevirtualId = $data->asr_id;

        $response = $this->repo->delete($ambientevirtualId);

        $this->assertEquals(1, $response);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
