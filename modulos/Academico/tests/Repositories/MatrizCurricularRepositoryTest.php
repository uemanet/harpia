<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modulos\Academico\Repositories\MatrizCurricularRepository;
use Modulos\Academico\Models\MatrizCurricular;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

use Modulos\Academico\Models\Curso;

class MatrizCurricularRepositoryTest extends TestCase
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

        $this->repo = $this->app->make(MatrizCurricularRepository::class);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testPaginateWithoutParameters()
    {
        factory(MatrizCurricular::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(MatrizCurricular::class, 2)->create();

        $sort = [
            'field' => 'mtc_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response[0]->mtc_id);
    }

    public function testPaginateWithSearch()
    {
        factory(MatrizCurricular::class, 2)->create();

        factory(MatrizCurricular::class)->create([
            'mtc_id' => 3,
        ]);

        $search = [
            [
                'field' => 'mtc_id',
                'type' => '=',
                'term' => 1
            ]
        ];

        $response = $this->repo->paginate(null, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertCount(1, $response);
    }

    public function testPaginateWithSearchAndOrder()
    {
        factory(MatrizCurricular::class, 2)->create();

        $sort = [
            'field' => 'mtc_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'mtc_id',
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
        $curso = factory(Curso::class)->create();
        factory(MatrizCurricular::class, 2)->create(['mtc_crs_id' => $curso->crs_id]);

        $response = $this->repo->paginateRequestByCurso($curso->crs_id);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testPaginateRequestWithParameters()
    {
        $curso = factory(Curso::class)->create();
        factory(MatrizCurricular::class, 2)->create(['mtc_crs_id' => $curso->crs_id]);

        $requestParameters = [
            'page' => '1',
            'field' => 'mtc_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequestByCurso($curso->crs_id, $requestParameters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testCreate()
    {
        $response = factory(MatrizCurricular::class)->create();

        $data = $response->toArray();

        $this->assertInstanceOf(MatrizCurricular::class, $response);

        $this->assertArrayHasKey('mtc_id', $data);
    }

    public function testFind()
    {
        $dados = factory(MatrizCurricular::class)->create();
        // Recupera id do curso a partir do Factory
        // Um Accessor é usado no model para retornar o nome do curso em vez de seu id
        $data = $dados->first()->toArray();
        // Retorna para date format americano antes de comparar com o banco
        $data['mtc_data'] = Carbon::createFromFormat('d/m/Y', $data['mtc_data'])->toDateString();
        $this->assertDatabaseHas('acd_matrizes_curriculares', $data);
    }

    public function testUpdate()
    {
        $data = factory(MatrizCurricular::class)->create();

        $updateArray = $data->toArray();
        $updateArray['mtc_descricao'] = 'abcde_edcba';

        $matrizCurricularId = $updateArray['mtc_id'];
        unset($updateArray['mtc_id']);

        $response = $this->repo->update($updateArray, $matrizCurricularId, 'mtc_id');

        $this->assertEquals(1, $response);
    }

    public function testUpdateReturn0()
    {
        $response = $this->repo->update([], 1, 'mtc_id');

        $this->assertEquals(0, $response);
    }

    public function testUpdateWithoutAttribute()
    {
        $data = factory(MatrizCurricular::class)->create();

        $updateArray = $data->toArray();
        $updateArray['mtc_descricao'] = 'abcde_edcba';

        $matrizCurricularId = $updateArray['mtc_id'];
        unset($updateArray['mtc_id']);

        $response = $this->repo->update($updateArray, $matrizCurricularId);

        $this->assertEquals(1, $response);
    }

    public function testFindAllByCurso()
    {
        $matriz = factory(MatrizCurricular::class)->create();
        $response = $this->repo->findAllByCurso($matriz->curso->crs_id);

        $this->assertNotEmpty($response, '');
    }

    public function testFindByOfertaCurso()
    {
        $ofertacurso = factory(\Modulos\Academico\Models\OfertaCurso::class)->create();

        $response = $this->repo->findByOfertaCurso($ofertacurso->ofc_id);

        $this->assertNotEmpty($response, '');
    }

    public function testGetDisciplinasByMatrizId()
    {
        $modulodisciplina = factory(\Modulos\Academico\Models\ModuloDisciplina::class)->create();

        $options = ['mdc_id' => $modulodisciplina->mdc_id];

        $response = $this->repo->getDisciplinasByMatrizId($modulodisciplina->modulo->matriz->mtc_id, $options);

        $this->assertNotEmpty($response);
    }

    public function testVerifyIfDisciplinaExistsInMatriz()
    {
        $modulodisciplina = factory(\Modulos\Academico\Models\ModuloDisciplina::class)->create();

        $response = $this->repo->verifyIfDisciplinaExistsInMatriz($modulodisciplina->modulo->matriz->mtc_id, $modulodisciplina->disciplina->dis_id);

        $this->assertEquals($response, true);
    }

    public function testVerifyIfDisciplinaExistsInMatrizReturnFalse()
    {
        $response = $this->repo->verifyIfDisciplinaExistsInMatriz(1, 1);

        $this->assertEquals($response, false);
    }

    public function testVerifyIfNomeDisciplinaExistsInMatriz()
    {
        $modulodisciplina = factory(\Modulos\Academico\Models\ModuloDisciplina::class)->create();

        $response = $this->repo->verifyIfNomeDisciplinaExistsInMatriz($modulodisciplina->modulo->matriz->mtc_id, $modulodisciplina->disciplina->dis_nome);

        $this->assertEquals($response, true);
    }

    public function testVerifyIfNomeDisciplinaExistsInMatrizReturnFalse()
    {
        $modulodisciplina = factory(\Modulos\Academico\Models\ModuloDisciplina::class)->create();

        $response = $this->repo->verifyIfNomeDisciplinaExistsInMatriz($modulodisciplina->modulo->matriz->mtc_id, 'Disciplina');

        $this->assertEquals($response, false);
    }

    public function testVerifyIfExistsDisciplinaTccInMatriz()
    {
        $modulodisciplina = factory(\Modulos\Academico\Models\ModuloDisciplina::class)->create(['mdc_tipo_disciplina' => 'obrigatoria']);

        $response = $this->repo->verifyIfExistsDisciplinaTccInMatriz($modulodisciplina->modulo->matriz->mtc_id);

        $this->assertEquals($response, false);
    }

    public function testVerifyIfExistsDisciplinaTccInMatrizReturnTrue()
    {
        $modulodisciplina = factory(\Modulos\Academico\Models\ModuloDisciplina::class)->create(['mdc_tipo_disciplina' => 'tcc']);

        $response = $this->repo->verifyIfExistsDisciplinaTccInMatriz($modulodisciplina->modulo->matriz->mtc_id);

        $this->assertEquals($response, true);
    }

    public function testDelete()
    {
        $data = factory(MatrizCurricular::class)->create();
        $matrizCurricularId = $data->mtc_id;

        $response = $this->repo->delete($matrizCurricularId);

        $this->assertEquals(1, $response);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
