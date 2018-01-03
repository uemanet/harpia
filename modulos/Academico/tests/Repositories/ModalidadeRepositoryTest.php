<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modulos\Academico\Repositories\ModalidadeRepository;
use Modulos\Academico\Models\Modalidade;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;

class ModalidadeRepositoryTest extends TestCase
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

        $this->repo = $this->app->make(ModalidadeRepository::class);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Modalidade::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Modalidade::class, 2)->create();

        $sort = [
            'field' => 'mdl_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response[0]->mdl_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Modalidade::class, 2)->create();

        factory(Modalidade::class)->create([
            'mdl_nome' => 'agronomia',
        ]);

        $search = [
            [
                'field' => 'mdl_nome',
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
        factory(Modalidade::class, 2)->create();

        $sort = [
            'field' => 'mdl_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'mdl_id',
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
        factory(Modalidade::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'mdl_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testCreate()
    {
        $response = factory(Modalidade::class)->create();

        $data = $response->toArray();

        $this->assertInstanceOf(Modalidade::class, $response);

        $this->assertArrayHasKey('mdl_id', $data);
    }

    public function testUpdate()
    {
        $data = factory(Modalidade::class)->create();

        $updateArray = $data->toArray();
        $updateArray['mdl_nome'] = 'abcde_edcba';

        $centroId = $updateArray['mdl_id'];
        unset($updateArray['mdl_id']);

        $response = $this->repo->update($updateArray, $centroId, 'mdl_id');

        $this->assertEquals(1, $response);
    }

    public function testDelete()
    {
        $data = factory(Modalidade::class)->create();
        $id = $data->mdl_id;

        $response = $this->repo->delete($id);

        $this->assertEquals(1, $response);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
