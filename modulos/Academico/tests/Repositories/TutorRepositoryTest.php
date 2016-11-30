<?php

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Artisan;
use Modulos\Academico\Repositories\TutorRepository;

class TutorRepositoryTest extends TestCase
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

        $this->repo = $this->app->make(TutorRepository::class);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Modulos\Academico\Models\Tutor::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Modulos\Academico\Models\Tutor::class, 2)->create();

        $sort = [
            'field' => 'tut_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response[0]->tut_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Modulos\Academico\Models\Tutor::class, 2)->create();

        factory(Modulos\Academico\Models\Tutor::class)->create([
            'tut_pes_id' => factory(Modulos\Geral\Models\Pessoa::class)->create([
                'pes_nome' => 'Tutor'
            ])->pes_id
        ]);

        $search = [
            [
                'field' => 'pes_nome',
                'type' => 'like',
                'term' => 'Tutor'
            ]
        ];

        $response = $this->repo->paginate(null, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertCount(1, $response);
    }

    public function testPaginateWithSearchAndOrder()
    {
        factory(Modulos\Academico\Models\Tutor::class, 2)->create();

        $sort = [
            'field' => 'tut_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'tut_id',
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
        factory(Modulos\Academico\Models\Tutor::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'tut_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testCreate()
    {
        $response = factory(Modulos\Academico\Models\Tutor::class)->create();

        $data = $response->toArray();

        $this->assertInstanceOf(\Modulos\Academico\Models\Tutor::class, $response);

        $this->assertArrayHasKey('tut_id', $data);
    }

    public function testFind()
    {
        $data = factory(Modulos\Academico\Models\Tutor::class)->create();

        $this->seeInDatabase('acd_tutores', $data->toArray());
    }

    public function testDelete()
    {
        $data = factory(Modulos\Academico\Models\Tutor::class)->create();
        $tutorId = $data->tut_id;

        $response = $this->repo->delete($tutorId);

        $this->assertEquals(1, $response);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
