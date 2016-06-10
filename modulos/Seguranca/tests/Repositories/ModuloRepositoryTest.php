<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modulos\Seguranca\Repositories\ModuloRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Artisan;

class ModuloRepositoryTest extends TestCase
{
    use DatabaseTransactions,
        WithoutMiddleware;

    protected $repo;
    protected $data;

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

        $this->repo = $this->app->make(ModuloRepository::class);

        $this->data = [
            'mod_nome' => 'Modulo test',
            'mod_rota' => 'test',
            'mod_descricao' => 'Descricao do modulo',
            'mod_icone' => 'fa fa-cog',
            'mod_ativo' => 1
        ];
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(0, $response->total());
    }

    public function testPaginateWithoutParameters()
    {
        $this->insertDataInDatabase(2);

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        $this->insertDataInDatabase(2);

        $sort = [
            'field' => 'mod_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response[0]->mod_id);
    }

    public function testPaginateWithSearch()
    {
        $this->insertDataInDatabase(2);

        $data = $this->data;
        $data['mod_nome'] = 'seguranca';
        $this->insertDataInDatabase(1, $data);

        $search = [
            [
                'field' => 'mod_nome',
                'type' => 'like',
                'term' => 'seguranca'
            ]
        ];

        $response = $this->repo->paginate(null, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertCount(1, $response);
    }

    public function testPaginateWithSearchAndOrder()
    {
        $this->insertDataInDatabase(2);

        $sort = [
            'field' => 'mod_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'mod_id',
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
        $this->insertDataInDatabase(2);

        $requestParameters = [
            'page' => '1',
            'field' => 'mod_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testCreate()
    {
        $response = $this->insertDataInDatabase(1, $this->data);

        $data = $response->toArray();

        $this->assertInstanceOf(\Modulos\Seguranca\Models\Modulo::class, $response);

        $this->assertArrayHasKey('mod_id', $data);
    }

    public function testFind()
    {
        $data = $this->insertDataInDatabase(1, $this->data);

        $this->seeInDatabase('seg_modulos', $data->toArray());
    }

    public function testUpdate()
    {
        $data = $this->insertDataInDatabase(1, $this->data);

        $updateArray = $data->toArray();
        $updateArray['mod_nome'] = 'abcde_edcba';

        $moduloId = $updateArray['mod_id'];
        unset($updateArray['mod_id']);

        $response = $this->repo->update($updateArray, $moduloId, 'mod_id');

        $this->assertEquals(1, $response);
    }

    public function testDelete()
    {
        $data = $this->insertDataInDatabase(1, $this->data);
        $moduloId = $data->mod_id;

        $response = $this->repo->delete($moduloId);

        $this->assertEquals(1, $response);
    }

    private function insertDataInDatabase($numberOfRows = 1, $data = null)
    {
        if (!$data) {
            $data = $this->data;
        }

        for ($i = 0; $i < $numberOfRows; $i++) {
            $lastInsertedData = $this->repo->create($data);
        }

        return $lastInsertedData;
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}