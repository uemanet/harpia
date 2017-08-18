<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modulos\Seguranca\Repositories\MenuItemRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;

class MenuItemRepositoryTest extends TestCase
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

        $this->repo = $this->app->make(MenuItemRepository::class);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Modulos\Seguranca\Models\MenuItem::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Modulos\Seguranca\Models\MenuItem::class, 2)->create();

        $sort = [
            'field' => 'mit_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response[0]->mit_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Modulos\Seguranca\Models\MenuItem::class, 2)->create();

        factory(Modulos\Seguranca\Models\MenuItem::class)->create([
            'mit_nome' => 'seguranca',
        ]);

        $search = [
            [
                'field' => 'mit_nome',
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
        factory(Modulos\Seguranca\Models\MenuItem::class, 2)->create();

        $sort = [
            'field' => 'mit_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'mit_id',
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
        factory(Modulos\Seguranca\Models\MenuItem::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'mit_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testCreate()
    {
        $response = factory(Modulos\Seguranca\Models\MenuItem::class)->create();

        $data = $response->toArray();

        $this->assertInstanceOf(\Modulos\Seguranca\Models\MenuItem::class, $response);

        $this->assertArrayHasKey('mit_id', $data);
    }

    public function testFind()
    {
        $data = factory(Modulos\Seguranca\Models\MenuItem::class)->create();

        $this->seeInDatabase('seg_menu_itens', $data->toArray());
    }

    public function testUpdate()
    {
        $data = factory(Modulos\Seguranca\Models\MenuItem::class)->create();

        $updateArray = $data->toArray();
        $updateArray['mit_nome'] = 'abcde_edcba';

        $menuitemId = $updateArray['mit_id'];
        unset($updateArray['mit_id']);

        $response = $this->repo->update($updateArray, $menuitemId, 'mit_id');

        $this->assertEquals(1, $response);
    }

    public function testDelete()
    {
        $data = factory(Modulos\Seguranca\Models\MenuItem::class)->create();
        $menuitemId = $data->mit_id;

        $response = $this->repo->delete($menuitemId);

        $this->assertEquals(1, $response);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
