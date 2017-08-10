<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modulos\Integracao\Repositories\AmbienteTurmaRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;

class AmbienteTurmaRepositoryTest extends TestCase
{
    use DatabaseTransactions,
        WithoutMiddleware;

    protected $repo;

    public function createApplication()
    {
        putenv('DB_CONNECTION=sqlite_testing');

        $app = require __DIR__ . '/../../../bootstrap/app.php';

        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

        return $app;
    }

    public function setUp()
    {
        parent::setUp();

        Artisan::call('modulos:migrate');

        $this->repo = $this->app->make(AmbienteTurmaRepository::class);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Modulos\Integracao\Models\AmbienteTurma::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Modulos\Integracao\Models\AmbienteTurma::class, 2)->create();

        $sort = [
            'field' => 'atr_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertEquals(2, $response[0]->atr_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Modulos\Integracao\Models\AmbienteTurma::class, 2)->create();

        factory(Modulos\Integracao\Models\AmbienteTurma::class)->create([
            'atr_amb_id' => 1,
        ]);

        $search = [
            [
                'field' => 'atr_amb_id',
                'type' => '=',
                'term' => 1
            ]
        ];

        $response = $this->repo->paginate(null, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());

        $this->assertEquals(1, $response[0]->atr_amb_id);
    }

    public function testPaginateWithSearchAndOrder()
    {
        factory(Modulos\Integracao\Models\AmbienteTurma::class, 2)->create();

        $sort = [
            'field' => 'atr_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'atr_id',
                'type' => '>',
                'term' => '1'
            ]
        ];

        $response = $this->repo->paginate($sort, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());

        $this->assertEquals(2, $response[0]->atr_id);
    }

    public function testPaginateRequest()
    {
        factory(Modulos\Integracao\Models\AmbienteTurma::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'atr_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testCreate()
    {
        $response = factory(Modulos\Integracao\Models\AmbienteTurma::class)->create();

        $this->assertInstanceOf(\Modulos\Integracao\Models\AmbienteTurma::class, $response);

        $this->assertArrayHasKey('atr_id', $response->toArray());
    }

    public function testFind()
    {
        $data = factory(Modulos\Integracao\Models\AmbienteTurma::class)->create();

        $this->seeInDatabase('int_ambientes_turmas', $data->toArray());
    }

    public function testUpdate()
    {
        $ambiente = factory(Modulos\Integracao\Models\AmbienteVirtual::class)->create();
        $data = factory(Modulos\Integracao\Models\AmbienteTurma::class)->create();

        $updateArray = $data->toArray();
        $updateArray['atr_amb_id'] = $ambiente->amb_id;

        $ambientevirtualdId = $updateArray['atr_id'];
        unset($updateArray['atr_id']);

        $response = $this->repo->update($updateArray, $ambientevirtualdId, 'atr_id');

        $this->assertEquals(1, $response);
    }

    public function testDelete()
    {
        $data = factory(Modulos\Integracao\Models\AmbienteTurma::class)->create();
        $ambientevirtualId = $data->atr_id;

        $response = $this->repo->delete($ambientevirtualId);

        $this->assertEquals(1, $response);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
