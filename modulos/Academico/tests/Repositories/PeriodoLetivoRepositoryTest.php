<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modulos\Academico\Repositories\PeriodoLetivoRepository;
use Modulos\Academico\Models\PeriodoLetivo;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;

class PeriodoLetivoRepositoryTest extends TestCase
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

        $this->repo = $this->app->make(PeriodoLetivoRepository::class);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testPaginateWithoutParameters()
    {
        factory(PeriodoLetivo::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(PeriodoLetivo::class, 2)->create();

        $sort = [
            'field' => 'per_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response[0]->per_id);
    }

    public function testPaginateWithSearch()
    {
        factory(PeriodoLetivo::class, 2)->create();

        factory(PeriodoLetivo::class)->create([
            'per_inicio' => '15-04-2008',
        ]);

        $search = [
            [
                'field' => 'per_inicio',
                'type' => 'like',
                'term' => '15-04-2008'
            ]
        ];

        $response = $this->repo->paginate(null, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertCount(1, $response);
    }

    public function testPaginateWithSearchAndOrder()
    {
        factory(PeriodoLetivo::class, 2)->create();

        $sort = [
            'field' => 'per_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'per_id',
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
        factory(PeriodoLetivo::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'per_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testCreate()
    {
        $response = factory(PeriodoLetivo::class)->create();

        $data = $response->toArray();

        $this->assertInstanceOf(PeriodoLetivo::class, $response);

        $this->assertArrayHasKey('per_id', $data);
    }

    public function testFind()
    {
        $data = factory(PeriodoLetivo::class)->create();

        $this->seeInDatabase('acd_periodos_letivos', $data->toArray());
    }

    public function testUpdate()
    {
        $data = factory(PeriodoLetivo::class)->create();

        $updateArray = $data->toArray();
        $updateArray['per_fim'] = '10-02-2005';

        $periodoLetivoId = $updateArray['per_id'];
        unset($updateArray['per_id']);

        $response = $this->repo->update($updateArray, $periodoLetivoId, 'per_id');

        $this->assertEquals(1, $response);
    }

    public function testDelete()
    {
        $data = factory(PeriodoLetivo::class)->create();
        $periodoLetivoId = $data->per_id;

        $response = $this->repo->delete($periodoLetivoId);

        $this->assertEquals(1, $response);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
