<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modulos\Academico\Repositories\TurmaRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;

class TurmaRepositoryTest extends TestCase
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

        $this->repo = $this->app->make(TurmaRepository::class);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Modulos\Academico\Models\Turma::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Modulos\Academico\Models\Turma::class, 2)->create();

        $sort = [
            'field' => 'trm_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertEquals(2, $response[0]->trm_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Modulos\Academico\Models\Turma::class, 2)->create();

        factory(Modulos\Academico\Models\Turma::class)->create([
            'trm_nome' => 'turma A',
        ]);

        $search = [
            [
                'field' => 'trm_nome',
                'type' => 'like',
                'term' => 'turma A'
            ]
        ];

        $response = $this->repo->paginate(null, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());

        $this->assertEquals('turma A', $response[0]->trm_nome);
    }

    public function testPaginateWithSearchAndOrder()
    {
        factory(Modulos\Academico\Models\Turma::class, 2)->create();

        $sort = [
            'field' => 'trm_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'trm_id',
                'type' => '>',
                'term' => '1'
            ]
        ];

        $response = $this->repo->paginate($sort, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());

        $this->assertEquals(2, $response[0]->trm_id);
    }

    public function testPaginateRequest()
    {
        factory(Modulos\Academico\Models\Turma::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'trm_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testCreate()
    {
        $response = factory(Modulos\Academico\Models\Turma::class)->create();

        $this->assertInstanceOf(\Modulos\Academico\Models\Turma::class, $response);

        $this->assertArrayHasKey('trm_id', $response->toArray());
    }

    public function testFind()
    {
        $data = factory(Modulos\Academico\Models\Turma::class)->create();

        $this->seeInDatabase('acd_turmas', $data->toArray());
    }

    public function testUpdate()
    {
        $data = factory(Modulos\Academico\Models\Turma::class)->create();

        $updateArray = $data->toArray();
        $updateArray['trm_nome'] = 'abcde_edcba';

        $turmadId = $updateArray['trm_id'];
        unset($updateArray['trm_id']);

        $response = $this->repo->update($updateArray, $turmadId, 'trm_id');

        $this->assertEquals(1, $response);
    }

    public function testDelete()
    {
        $data = factory(Modulos\Academico\Models\Turma::class)->create();
        $turmaId = $data->trm_id;

        $response = $this->repo->delete($turmaId);

        $this->assertEquals(1, $response);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
