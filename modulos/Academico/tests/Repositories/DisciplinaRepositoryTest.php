<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modulos\Academico\Repositories\DisciplinaRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;

class DisciplinaRepositoryTest extends TestCase
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

        $this->repo = $this->app->make(DisciplinaRepository::class);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testPaginateWithoutParameters()
    {
        factory(\Modulos\Academico\Models\Disciplina::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(\Modulos\Academico\Models\Disciplina::class, 2)->create();

        $sort = [
            'field' => 'dis_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response[0]->dis_id);
    }

    public function testPaginateWithSearch()
    {
        factory(\Modulos\Academico\Models\Disciplina::class, 2)->create();

        factory(\Modulos\Academico\Models\Disciplina::class)->create([
            'dis_nome' => 'São Luís 1',
        ]);

        $search = [
            [
                'field' => 'dis_nome',
                'type' => 'like',
                'term' => 'São Luís 1'
            ]
        ];

        $response = $this->repo->paginate(null, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertCount(1, $response);
    }

    public function testPaginateWithSearchAndOrder()
    {
        factory(\Modulos\Academico\Models\Disciplina::class, 2)->create();

        $sort = [
            'field' => 'dis_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'dis_id',
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
        factory(\Modulos\Academico\Models\Disciplina::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'dis_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testCreate()
    {
        $response = factory(\Modulos\Academico\Models\Disciplina::class)->create();

        $data = $response->toArray();

        $this->assertInstanceOf(\Modulos\Academico\Models\Disciplina::class, $response);

        $this->assertArrayHasKey('dis_id', $data);
    }

    public function testFind()
    {
        $data = factory(\Modulos\Academico\Models\Disciplina::class)->create();

        $this->seeInDatabase('acd_disciplinas', $data->toArray());
    }

    public function testUpdate()
    {
        $data = factory(\Modulos\Academico\Models\Disciplina::class)->create();

        $updateArray = $data->toArray();
        $updateArray['dis_nome'] = 'abcde_edcba';

        $disciplinaId = $updateArray['dis_id'];
        unset($updateArray['dis_id']);

        $response = $this->repo->update($updateArray, $disciplinaId, 'dis_id');

        $this->assertEquals(1, $response);
    }

    public function testValidacaoReturnFalse()
    {
      $response = factory(\Modulos\Academico\Models\Disciplina::class)->create();

      $data = $response->toArray();

      $response = $this->repo->validacao($data);

      $this->assertEquals($response, false);
    }

    public function testValidacaoReturnTrue()
    {
      $response = factory(\Modulos\Academico\Models\Disciplina::class)->create();

      $data = $response->toArray();
      $data['dis_nome'] = 'Disciplina';

      $response = $this->repo->validacao($data);

      $this->assertEquals($response, true);
    }

    public function testValidacaoWithId()
    {
      $response = factory(\Modulos\Academico\Models\Disciplina::class)->create();

      $data = $response->toArray();

      $response = $this->repo->validacao($data, $data['dis_id']);

      $this->assertEquals($response, true);
    }

    public function testBuscarDisciplinasDaMatriz()
    {
      $response = factory(\Modulos\Academico\Models\ModuloDisciplina::class)->create();

      $response = $this->repo->buscar($response->modulo->matriz->mtc_id, $response->disciplina->dis_nome);

      $this->assertEmpty($response, '');
    }

    public function testBuscarDisciplinas()
    {
      $response = factory(\Modulos\Academico\Models\ModuloDisciplina::class)->create();

      $disciplina = factory(\Modulos\Academico\Models\Disciplina::class)->create();

      $response = $this->repo->buscar($response->modulo->matriz->mtc_id, $disciplina->dis_nome);

      $this->assertNotEmpty($response, '');
    }

    public function testGetDisciplinasModulosAnteriores()
    {
      $response = factory(\Modulos\Academico\Models\ModuloDisciplina::class)->create();

      $response = $this->repo->getDisciplinasModulosAnteriores($response->modulo->matriz->mtc_id, $response->modulo->mdo_id);

      $this->assertEmpty($response, '');
    }

    public function testDelete()
    {
        $data = factory(\Modulos\Academico\Models\Disciplina::class)->create();
        $disciplinaId = $data->dis_id;

        $response = $this->repo->delete($disciplinaId);

        $this->assertEquals(1, $response);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
