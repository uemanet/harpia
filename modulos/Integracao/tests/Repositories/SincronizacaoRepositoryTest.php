<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modulos\Integracao\Repositories\SincronizacaoRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;

class SincronizacaoRepositoryTest extends TestCase
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

        $this->repo = $this->app->make(SincronizacaoRepository::class);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Modulos\Integracao\Models\Sincronizacao::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Modulos\Integracao\Models\Sincronizacao::class, 2)->create();

        $sort = [
            'field' => 'sym_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertEquals(2, $response[0]->sym_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Modulos\Integracao\Models\Sincronizacao::class, 2)->create();

        factory(Modulos\Integracao\Models\Sincronizacao::class)->create([
            'sym_mensagem' => 'icatu',
        ]);

        $search = [
            [
                'field' => 'sym_mensagem',
                'type' => 'like',
                'term' => 'icatu'
            ]
        ];

        $response = $this->repo->paginate(null, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());

        $this->assertEquals('icatu', $response[0]->sym_mensagem);
    }

    public function testPaginateWithSearchAndOrder()
    {
        factory(Modulos\Integracao\Models\Sincronizacao::class, 2)->create();

        $sort = [
            'field' => 'sym_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'sym_id',
                'type' => '>',
                'term' => '1'
            ]
        ];

        $response = $this->repo->paginate($sort, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());

        $this->assertEquals(2, $response[0]->sym_id);
    }

    public function testPaginateRequest()
    {
        factory(Modulos\Integracao\Models\Sincronizacao::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'sym_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testCreate()
    {
        $response = factory(Modulos\Integracao\Models\Sincronizacao::class)->create();

        $this->assertInstanceOf(\Modulos\Integracao\Models\Sincronizacao::class, $response);

        $this->assertArrayHasKey('sym_id', $response->toArray());
    }

    public function testFind()
    {
        $data = factory(Modulos\Integracao\Models\Sincronizacao::class)->create();

        $this->seeInDatabase('int_sync_moodle', $data->toArray());
    }


    public function testFindBy()
    {
        $data = [
            'sym_table' => 'gra_pessoas',
            'sym_action' => 'UPDATE',
        ];

        $sync = factory(Modulos\Integracao\Models\Sincronizacao::class)->create($data);

        $recovered = $this->repo->findBy($data)->last();

        $this->seeInDatabase('int_sync_moodle', $sync->toArray());
        $this->assertEquals($sync->toArray(), $recovered->toArray());
    }

    public function testUpdate()
    {
        $data = factory(Modulos\Integracao\Models\Sincronizacao::class)->create();

        $updateArray = $data->toArray();
        $updateArray['sym_mensagem'] = 'abcde_edcba';

        $syncId = $updateArray['sym_id'];
        unset($updateArray['sym_id']);

        $response = $this->repo->update($updateArray, $syncId, 'sym_id');

        $this->assertEquals(1, $response);
    }

    public function testUpdateSyncMoodle()
    {
        $data = factory(Modulos\Integracao\Models\Sincronizacao::class)->create([
            'sym_table' => 'gra_pessoas',
            'sym_table_id' => 1,
            'sym_action' => 'UPDATE',
        ]);

        $updateArray = $data->toArray();
        $updateArray['sym_mensagem'] = 'abcde_edcba';

        $response = $this->repo->updateSyncMoodle($updateArray);

        $this->assertEquals($data->sym_id, $response);
    }

    public function testDelete()
    {
        $data = factory(Modulos\Integracao\Models\Sincronizacao::class)->create();
        $syncId = $data->sym_id;

        $response = $this->repo->delete($syncId);

        $this->assertEquals(1, $response);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
