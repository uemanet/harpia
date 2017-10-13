<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modulos\Academico\Repositories\PoloRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;

class PoloRepositoryTest extends TestCase
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

        $this->repo = $this->app->make(PoloRepository::class);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Modulos\Academico\Models\Polo::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Modulos\Academico\Models\Polo::class, 2)->create();

        $sort = [
            'field' => 'pol_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertEquals(2, $response[0]->pol_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Modulos\Academico\Models\Polo::class, 2)->create();

        factory(Modulos\Academico\Models\Polo::class)->create([
            'pol_nome' => 'icatu',
        ]);

        $search = [
            [
                'field' => 'pol_nome',
                'type' => 'like',
                'term' => 'icatu'
            ]
        ];

        $response = $this->repo->paginate(null, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());

        $this->assertEquals('icatu', $response[0]->pol_nome);
    }

    public function testPaginateWithSearchAndOrder()
    {
        factory(Modulos\Academico\Models\Polo::class, 2)->create();

        $sort = [
            'field' => 'pol_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'pol_id',
                'type' => '>',
                'term' => '1'
            ]
        ];

        $response = $this->repo->paginate($sort, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());

        $this->assertEquals(2, $response[0]->pol_id);
    }

    public function testPaginateRequest()
    {
        factory(Modulos\Academico\Models\Polo::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'pol_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testCreate()
    {
        $response = factory(Modulos\Academico\Models\Polo::class)->create();

        $this->assertInstanceOf(\Modulos\Academico\Models\Polo::class, $response);

        $this->assertArrayHasKey('pol_id', $response->toArray());
    }

    public function testFind()
    {
        $data = factory(Modulos\Academico\Models\Polo::class)->create();

        $this->assertDatabaseHas('acd_polos', $data->toArray());
    }

    public function testUpdate()
    {
        $data = factory(Modulos\Academico\Models\Polo::class)->create();

        $updateArray = $data->toArray();
        $updateArray['pol_nome'] = 'abcde_edcba';

        $polodId = $updateArray['pol_id'];
        unset($updateArray['pol_id']);

        $response = $this->repo->update($updateArray, $polodId, 'pol_id');

        $this->assertEquals(1, $response);
    }

    public function testDelete()
    {
        $data = factory(Modulos\Academico\Models\Polo::class)->create();
        $poloId = $data->pol_id;

        $response = $this->repo->delete($poloId);

        $this->assertEquals(1, $response);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
