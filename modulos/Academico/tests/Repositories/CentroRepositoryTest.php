<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modulos\Academico\Repositories\CentroRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;

class CentroRepositoryTest extends TestCase
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

        $this->repo = $this->app->make(CentroRepository::class);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Modulos\Academico\Models\Centro::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Modulos\Academico\Models\Centro::class, 2)->create();

        $sort = [
            'field' => 'cen_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response[0]->cen_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Modulos\Academico\Models\Centro::class, 2)->create();

        factory(Modulos\Academico\Models\Centro::class)->create([
            'cen_nome' => 'agronomia',
        ]);

        $search = [
            [
                'field' => 'cen_nome',
                'type' => 'like',
                'term' => 'agronomia'
            ]
        ];

        $response = $this->repo->paginate(null, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertCount(1, $response);
    }

    public function testPaginateWithSearchAndOrder()
    {
        factory(Modulos\Academico\Models\Centro::class, 2)->create();

        $sort = [
            'field' => 'cen_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'cen_id',
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
        factory(Modulos\Academico\Models\Centro::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'cen_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testCreate()
    {
        $response = factory(Modulos\Academico\Models\Centro::class)->create();

        $data = $response->toArray();

        $this->assertInstanceOf(Modulos\Academico\Models\Centro::class, $response);

        $this->assertArrayHasKey('cen_id', $data);
    }

    public function testFind()
    {
        $data = factory(Modulos\Academico\Models\Centro::class)->create();

        $this->seeInDatabase('acd_centros', $data->toArray());
    }

    public function testUpdate()
    {
        $data = factory(Modulos\Academico\Models\Centro::class)->create();

        $updateArray = $data->toArray();
        $updateArray['cen_nome'] = 'abcde_edcba';

        $centroId = $updateArray['cen_id'];
        unset($updateArray['cen_id']);

        $response = $this->repo->update($updateArray, $centroId, 'cen_id');

        $this->assertEquals(1, $response);
    }

    public function testDelete()
    {
        $data = factory(Modulos\Academico\Models\Centro::class)->create();
        $centroId = $data->cen_id;

        $response = $this->repo->delete($centroId);

        $this->assertEquals(1, $response);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
