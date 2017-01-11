<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\VinculoRepository;
use Modulos\Academico\Models\Curso;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;
use Auth;

class CursoRepositoryTest extends TestCase
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

    public function login()
    {
        $user = factory(Modulos\Seguranca\Models\Usuario::class)->create();

        $this->actingAs($user);
    }

    public function setUp()
    {
        parent::setUp();

        Artisan::call('modulos:migrate');

        $this->repo = $this->app->make(CursoRepository::class);
        $this->login();
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testPaginateWithoutParameters()
    {
        factory(\Modulos\Academico\Models\Vinculo::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(\Modulos\Academico\Models\Vinculo::class, 2)->create();

        $sort = [
            'field' => 'crs_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSearch()
    {
        factory(\Modulos\Academico\Models\Vinculo::class, 2)->create();

        $curso = factory(Curso::class)->create([
            'crs_nome' => 'eletrônica',
        ]);

        // Cria vinculo
        factory(\Modulos\Academico\Models\Vinculo::class)->create([
            'ucr_usr_id' => Auth::user()->usr_id,
            'ucr_crs_id' => $curso->crs_id
        ]);

        $search = [
            [
                'field' => 'crs_nome',
                'type' => 'like',
                'term' => 'eletrônica'
            ]
        ];

        $response = $this->repo->paginate(null, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertCount(1, $response);
    }

    public function testPaginateWithSearchAndOrder()
    {
        factory(\Modulos\Academico\Models\Vinculo::class, 2)->create();

        $sort = [
            'field' => 'crs_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'crs_id',
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
        factory(\Modulos\Academico\Models\Vinculo::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'crs_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testCreate()
    {
        $response = factory(Curso::class)->create();

        $data = $response->toArray();

        $this->assertInstanceOf(Curso::class, $response);

        $this->assertArrayHasKey('crs_id', $data);
    }

    public function testFind()
    {
        $dados = factory(Curso::class)->create();

        $data = $dados->toArray();

        // Retorna para date format americano antes de comparar com o banco
        $data['crs_data_autorizacao'] = Carbon::createFromFormat('d/m/Y', $data['crs_data_autorizacao'])->toDateString();

        $this->seeInDatabase('acd_cursos', $data);
    }

    public function testUpdate()
    {
        $data = factory(Curso::class)->create();

        $updateArray = $data->toArray();
        $updateArray['crs_nome'] = 'abcde_edcba';

        $cursoId = $updateArray['crs_id'];
        unset($updateArray['crs_id']);

        $response = $this->repo->update($updateArray, $cursoId, 'crs_id');

        $this->assertEquals(1, $response);
    }

    public function testDelete()
    {
        $data = factory(Curso::class)->create();
        $cursoId = $data->crs_id;

        $response = $this->repo->delete($cursoId);

        $this->assertEquals(1, $response);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
