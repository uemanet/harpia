<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modulos\Academico\Repositories\ProfessorRepository;
use Modulos\Academico\Models\Professor;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;

class ProfessorRepositoryTest extends TestCase
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

        $this->repo = $this->app->make(ProfessorRepository::class);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Professor::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Professor::class, 2)->create();

        $sort = [
            'field' => 'prf_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response[0]->prf_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Professor::class, 2)->create();

        factory(Professor::class)->create([
            'prf_matricula' => 'abc123',
        ]);

        $search = [
            [
                'field' => 'prf_matricula',
                'type' => 'like',
                'term' => 'abc123'
            ]
        ];

        $response = $this->repo->paginate(null, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertCount(1, $response);
    }

    public function testPaginateWithSearchAndOrder()
    {
        factory(Professor::class, 2)->create();

        $sort = [
            'field' => 'prf_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'prf_id',
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
        factory(Professor::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'prf_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testCreate()
    {
        $response = factory(Professor::class)->create();

        $data = $response->toArray();

        $this->assertInstanceOf(Professor::class, $response);

        $this->assertArrayHasKey('prf_id', $data);
    }

    public function testFind()
    {
        $data = factory(Professor::class)->create();

        $this->seeInDatabase('acd_professores', $data->toArray());
    }

    public function testUpdate()
    {
        $data = factory(Professor::class)->create();

        $updateArray = $data->toArray();
        $updateArray['prf_matricula'] = 'abcde_edcba';

        $professorId = $updateArray['prf_id'];
        unset($updateArray['prf_id']);

        $response = $this->repo->update($updateArray, $professorId, 'prf_id');

        $this->assertEquals(1, $response);
    }

    public function testDelete()
    {
        $data = factory(Professor::class)->create();
        $professorId = $data->prf_id;

        $response = $this->repo->delete($professorId);

        $this->assertEquals(1, $response);
    }

    public function testLists()
    {
    }


    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
