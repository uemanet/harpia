<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;

class AmbienteVirtualRepositoryTest extends TestCase
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

        $this->repo = $this->app->make(AmbienteVirtualRepository::class);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Modulos\Integracao\Models\AmbienteVirtual::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Modulos\Integracao\Models\AmbienteVirtual::class, 2)->create();

        $sort = [
            'field' => 'amb_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertEquals(2, $response[0]->amb_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Modulos\Integracao\Models\AmbienteVirtual::class, 2)->create();

        factory(Modulos\Integracao\Models\AmbienteVirtual::class)->create([
            'amb_nome' => 'icatu',
        ]);

        $search = [
            [
                'field' => 'amb_nome',
                'type' => 'like',
                'term' => 'icatu'
            ]
        ];

        $response = $this->repo->paginate(null, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());

        $this->assertEquals('icatu', $response[0]->amb_nome);
    }

    public function testPaginateWithSearchAndOrder()
    {
        factory(Modulos\Integracao\Models\AmbienteVirtual::class, 2)->create();

        $sort = [
            'field' => 'amb_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'amb_id',
                'type' => '>',
                'term' => '1'
            ]
        ];

        $response = $this->repo->paginate($sort, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());

        $this->assertEquals(2, $response[0]->amb_id);
    }

    public function testPaginateRequest()
    {
        factory(Modulos\Integracao\Models\AmbienteVirtual::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'amb_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testCreate()
    {
        $response = factory(Modulos\Integracao\Models\AmbienteVirtual::class)->create();

        $this->assertInstanceOf(\Modulos\Integracao\Models\AmbienteVirtual::class, $response);

        $this->assertArrayHasKey('amb_id', $response->toArray());
    }

    public function testFind()
    {
        $data = factory(Modulos\Integracao\Models\AmbienteVirtual::class)->create();

        $this->seeInDatabase('int_ambientes_virtuais', $data->toArray());
    }

    public function testUpdate()
    {
        $data = factory(Modulos\Integracao\Models\AmbienteVirtual::class)->create();

        $updateArray = $data->toArray();
        $updateArray['amb_nome'] = 'abcde_edcba';

        $ambienteVirtualId = $updateArray['amb_id'];
        unset($updateArray['amb_id']);

        $response = $this->repo->update($updateArray, $ambienteVirtualId, 'amb_id');

        $this->assertEquals(1, $response);
    }

    public function testDelete()
    {
        $data = factory(Modulos\Integracao\Models\AmbienteVirtual::class)->create();
        $ambienteVirtualId = $data->amb_id;

        $response = $this->repo->delete($ambienteVirtualId);

        $this->assertEquals(1, $response);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
