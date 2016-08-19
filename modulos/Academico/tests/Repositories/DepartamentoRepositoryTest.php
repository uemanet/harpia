<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modulos\Academico\Repositories\DepartamentoRepository;
use Modulos\Academico\Models\Departamento;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;

class DepartamentoRepositoryTest extends TestCase
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

        $this->repo = $this->app->make(DepartamentoRepository::class);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Departamento::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Departamento::class, 2)->create();

        $sort = [
            'field' => 'dep_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response[0]->dep_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Departamento::class, 2)->create();

        factory(Departamento::class)->create([
            'dep_nome' => 'agronomia',
        ]);

        $search = [
            [
                'field' => 'dep_nome',
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
        factory(Departamento::class, 2)->create();

        $sort = [
            'field' => 'dep_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'dep_id',
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
        factory(Departamento::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'dep_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testCreate()
    {
        $response = factory(Departamento::class)->create();

        $data = $response->toArray();

        $this->assertInstanceOf(Departamento::class, $response);

        $this->assertArrayHasKey('dep_id', $data);
    }

    public function testFind()
    {
        $data = factory(Departamento::class)->create();

        $this->seeInDatabase('acd_departamentos', $data->toArray());
    }

    public function testUpdate()
    {
        $data = factory(Departamento::class)->create();

        $updateArray = $data->toArray();
        $updateArray['dep_nome'] = 'abcde_edcba';

        $departamentoId = $updateArray['dep_id'];
        unset($updateArray['dep_id']);

        $response = $this->repo->update($updateArray, $departamentoId, 'dep_id');

        $this->assertEquals(1, $response);
    }

    public function testDelete()
    {
        $data = factory(Departamento::class)->create();
        $departamentoId = $data->dep_id;

        $response = $this->repo->delete($departamentoId);

        $this->assertEquals(1, $response);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
