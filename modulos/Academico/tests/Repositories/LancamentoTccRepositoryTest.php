<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modulos\Academico\Repositories\LancamentoTccRepository;
use Modulos\Academico\Repositories\ModuloDisciplinaRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

class LancamentoTccRepositoryTest extends TestCase
{
    use DatabaseTransactions,
        WithoutMiddleware;

    protected $repo;
    protected $modulodisciplinaRepository;
    protected $turmaRepository;

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

        $this->repo = $this->app->make(LancamentoTccRepository::class);
        $this->modulodisciplinaRepository = $this->app->make(ModuloDisciplinaRepository::class);
        $this->turmaRepository = $this->app->make(TurmaRepository::class);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testPaginateWithoutParameters()
    {
        factory(\Modulos\Academico\Models\LancamentoTcc::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(\Modulos\Academico\Models\LancamentoTcc::class, 2)->create();

        $sort = [
            'field' => 'ltc_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response[0]->ltc_id);
    }

    public function testPaginateWithSearch()
    {
        factory(\Modulos\Academico\Models\LancamentoTcc::class, 2)->create();

        factory(\Modulos\Academico\Models\LancamentoTcc::class)->create([
            'ltc_titulo' => 'Grafos Isomorfos',
        ]);

        $search = [
            [
                'field' => 'ltc_titulo',
                'type' => 'like',
                'term' => 'Grafos Isomorfos'
            ]
        ];

        $response = $this->repo->paginate(null, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertCount(1, $response);
    }

    public function testPaginateWithSearchAndOrder()
    {
        factory(\Modulos\Academico\Models\LancamentoTcc::class, 2)->create();

        $sort = [
            'field' => 'ltc_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'ltc_id',
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
        factory(\Modulos\Academico\Models\LancamentoTcc::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'ltc_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testCreate()
    {
        $response = factory(\Modulos\Academico\Models\LancamentoTcc::class)->create();

        $data = $response->toArray();

        $this->assertInstanceOf(\Modulos\Academico\Models\LancamentoTcc::class, $response);

        $this->assertArrayHasKey('ltc_id', $data);
    }

    public function testFind()
    {
        $data = factory(\Modulos\Academico\Models\LancamentoTcc::class)->create();

        $data = $data->toArray();

        // Retorna para date format americano antes de comparar com o banco
        $data['ltc_data_apresentacao'] = Carbon::createFromFormat('d/m/Y', $data['ltc_data_apresentacao'])->toDateString();

        $this->assertDatabaseHas('acd_lancamentos_tccs', $data);
    }

    public function testUpdate()
    {
        $data = factory(\Modulos\Academico\Models\LancamentoTcc::class)->create();

        $updateArray = $data->toArray();
        $updateArray['ltc_nome'] = 'abcde_edcba';

        $lancamentotccId = $updateArray['ltc_id'];
        unset($updateArray['ltc_id']);

        $response = $this->repo->update($updateArray, $lancamentotccId, 'ltc_id');

        $this->assertEquals(1, $response);
    }

    public function testFindDisciplinaByTurma()
    {
        $disciplina = factory(\Modulos\Academico\Models\Disciplina::class)->create();
        $modulodisciplina = factory(\Modulos\Academico\Models\ModuloDisciplina::class)->create();

        $mdc = $this->modulodisciplinaRepository->create([
        'dis_id' => $disciplina->dis_id,
        'mod_id' => $modulodisciplina->mdc_mdo_id,
        'mtc_id' => $modulodisciplina->modulo->matriz->mtc_id,
        'tipo_disciplina' => 'tcc',
        'mdc_pre_requisitos' => null
      ]);

        $mdc = $this->modulodisciplinaRepository->find($mdc['data']['mdc_id']);

        $OfertaCurso = factory(Modulos\Academico\Models\OfertaCurso::class)->create([
        'ofc_crs_id' => $mdc->modulo->matriz->curso->crs_id
      ]);

        $turma = factory(Modulos\Academico\Models\Turma::class)->create([
        'trm_ofc_id' => $OfertaCurso->ofc_id
      ]);

        $oferta = factory(Modulos\Academico\Models\OfertaDisciplina::class)->create([
        'ofd_trm_id' => $turma->trm_id,
        'ofd_mdc_id' => $mdc->mdc_id,

      ]);

        $response = $this->repo->findDisciplinaByTurma($turma->trm_id);

        $this->assertNotEmpty($response, '');
    }


    public function testDelete()
    {
        $data = factory(\Modulos\Academico\Models\LancamentoTcc::class)->create();
        $lancamentotccId = $data->ltc_id;

        $response = $this->repo->delete($lancamentotccId);

        $this->assertEquals(1, $response);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
