<?php

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Artisan;
use Modulos\Seguranca\Repositories\UsuarioRepository;

class UsuarioRepositoryTest extends TestCase
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

        $this->repo = $this->app->make(UsuarioRepository::class);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Modulos\Seguranca\Models\Usuario::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Modulos\Seguranca\Models\Usuario::class, 2)->create();

        $sort = [
            'field' => 'usr_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response[0]->usr_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Modulos\Seguranca\Models\Usuario::class, 2)->create();

        factory(Modulos\Seguranca\Models\Usuario::class)->create([
            'usr_usuario' => 'leitor@leitor.com',
            'usr_senha' => bcrypt('123456'),
            'usr_ativo' => 1,
            'usr_pes_id' => factory(Modulos\Geral\Models\Pessoa::class)->create()->pes_id
        ]);

        $search = [
            [
                'field' => 'usr_usuario',
                'type' => 'like',
                'term' => 'leitor@leitor.com'
            ]
        ];

        $response = $this->repo->paginate(null, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertCount(1, $response);
    }

    public function testPaginateWithSearchAndOrder()
    {
        factory(Modulos\Seguranca\Models\Usuario::class, 2)->create();

        $sort = [
            'field' => 'usr_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'usr_id',
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
        factory(Modulos\Seguranca\Models\Usuario::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'usr_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testCreate()
    {
        $response = factory(Modulos\Seguranca\Models\Usuario::class)->create();

        $data = $response->toArray();

        $this->assertInstanceOf(\Modulos\Seguranca\Models\Usuario::class, $response);

        $this->assertArrayHasKey('usr_id', $data);
    }

    public function testFind()
    {
        $data = factory(Modulos\Seguranca\Models\Usuario::class)->create();

        $this->assertDatabaseHas('seg_usuarios', $data->toArray());
    }

    public function testUpdate()
    {
        $data = factory(Modulos\Seguranca\Models\Usuario::class)->create();

        $updateArray = $data->toArray();
        $updateArray['usr_usuario'] = 'academico@academico.com';

        $usuarioId = $updateArray['usr_id'];
        unset($updateArray['usr_id']);

        $response = $this->repo->update($updateArray, $usuarioId, 'usr_id');

        $this->assertEquals(1, $response);
    }

    public function testDelete()
    {
        $data = factory(Modulos\Seguranca\Models\Usuario::class)->create();
        $usuarioId = $data->usr_id;

        $response = $this->repo->delete($usuarioId);

        $this->assertEquals(1, $response);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
