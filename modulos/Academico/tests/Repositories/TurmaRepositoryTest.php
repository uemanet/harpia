<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Integracao\Repositories\AmbienteTurmaRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;

class TurmaRepositoryTest extends TestCase
{
    use DatabaseTransactions,
        WithoutMiddleware;

    protected $repo;
    protected $AmbienteTurmarepo;

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

        $this->repo = $this->app->make(TurmaRepository::class);
        $this->AmbienteTurmarepo = $this->app->make(AmbienteTurmaRepository::class);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testPaginateWithoutParameters()
    {
        $oferta = factory(Modulos\Academico\Models\OfertaCurso::class)->create();
        $turmas = factory(Modulos\Academico\Models\Turma::class, 10)->create(['trm_ofc_id' => $oferta->ofc_id]);

        $response = $this->repo->paginateRequestByOferta($oferta->ofc_id);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        $oferta = factory(Modulos\Academico\Models\OfertaCurso::class)->create();
        $turmas = factory(Modulos\Academico\Models\Turma::class, 10)->create(['trm_ofc_id' => $oferta->ofc_id]);

        $sort = [
          'field' => 'trm_nome',
          'sort' => 'desc'
      ];

        $response = $this->repo->paginateRequestByOferta($oferta->ofc_id, $sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(2, $response[0]->trm_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Modulos\Academico\Models\Turma::class, 2)->create();

        factory(Modulos\Academico\Models\Turma::class)->create([
            'trm_nome' => 'turma A',
        ]);

        $search = [
            [
                'field' => 'trm_nome',
                'type' => 'like',
                'term' => 'turma A'
            ]
        ];

        $response = $this->repo->paginate(null, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());

        $this->assertEquals('turma A', $response[0]->trm_nome);
    }

    public function testPaginateWithSearchAndOrder()
    {
        factory(Modulos\Academico\Models\Turma::class, 2)->create();

        $sort = [
            'field' => 'trm_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'trm_id',
                'type' => '>',
                'term' => '1'
            ]
        ];

        $response = $this->repo->paginate($sort, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());

        $this->assertEquals(2, $response[0]->trm_id);
    }

    public function testPaginateRequest()
    {
        factory(Modulos\Academico\Models\Turma::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'trm_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }

    public function testCreate()
    {
        $response = factory(Modulos\Academico\Models\Turma::class)->create();
        $this->assertInstanceOf(\Modulos\Academico\Models\Turma::class, $response);
        $this->assertArrayHasKey('trm_id', $response->toArray());
    }

    public function testFind()
    {
        $data = factory(Modulos\Academico\Models\Turma::class)->create();
        $this->assertDatabaseHas('acd_turmas', $data->toArray());
    }

    public function testUpdate()
    {
        $data = factory(Modulos\Academico\Models\Turma::class)->create();

        $updateArray = $data->toArray();
        $updateArray['trm_nome'] = 'abcde_edcba';

        $turmadId = $updateArray['trm_id'];
        unset($updateArray['trm_id']);
        $response = $this->repo->update($updateArray, $turmadId, 'trm_id');
        $this->assertEquals(1, $response);
    }

    public function testfindAllByOfertaCurso()
    {
        $turma = factory(Modulos\Academico\Models\Turma::class)->create();
        $response = $this->repo->findAllByOfertaCurso($turma->ofertacurso->ofc_id);
        $this->assertNotEmpty($response);
    }

    public function testfindAllByOfertaCursoIntegrada()
    {
        $oferta = factory(Modulos\Academico\Models\OfertaCurso::class)->create();
        $turma = factory(Modulos\Academico\Models\Turma::class)->create(['trm_ofc_id' => $oferta->ofc_id, 'trm_integrada' => 1]);
        $ambiente = factory(Modulos\Integracao\Models\AmbienteVirtual::class)->create();
        $dados['atr_trm_id'] = $turma->trm_id;
        $dados['atr_amb_id'] = $ambiente->amb_id;

        $this->AmbienteTurmarepo->create($dados);
        $response = $this->repo->findAllByOfertaCursoIntegrada($oferta->ofc_id);
        $this->assertNotEmpty($response);
    }

    public function testfindAllByOfertaCursoNaoIntegrada()
    {
        $turma = factory(Modulos\Academico\Models\Turma::class)->create(['trm_integrada' => 0]);
        $response = $this->repo->findAllByOfertaCursoNaoIntegrada($turma->ofertacurso->ofc_id);
        $this->assertNotEmpty($response);
    }

    public function testfindCursoByTurma()
    {
        $turma = factory(Modulos\Academico\Models\Turma::class)->create();
        $response = $this->repo->findCursoByTurma($turma->trm_id);
        $this->assertNotEmpty($response);
    }

    public function testlistsAllById()
    {
        $turma = factory(Modulos\Academico\Models\Turma::class)->create();
        $response = $this->repo->listsAllById($turma->trm_id);
        $this->assertNotEmpty($response);
    }

    public function testgetTurmaPolosByMatriculas()
    {
        $matricula = factory(Modulos\Academico\Models\Matricula::class)->create();
        $response = $this->repo->getTurmaPolosByMatriculas($matricula->turma->trm_id);
        $this->assertNotEmpty($response);
    }

    public function testfindAllWithVagasDisponiveisByOfertaCurso()
    {
        $matricula = factory(Modulos\Academico\Models\Matricula::class)->create();
        $response = $this->repo->findAllWithVagasDisponiveisByOfertaCurso($matricula->turma->ofertacurso->ofc_id);
        $this->assertNotEmpty($response);
    }

    public function testpendenciasTurmaReturnTrue()
    {
        $matricula = factory(Modulos\Academico\Models\Matricula::class)->create();
        $response = $this->repo->pendenciasTurma($matricula->mat_trm_id);
        $this->assertEquals($response, true);
    }

    public function testpendenciasTurmaReturnFalse()
    {
        $response = $this->repo->pendenciasTurma(1);
        $this->assertEquals($response, false);
    }

    public function testDelete()
    {
        $data = factory(Modulos\Academico\Models\Turma::class)->create();

        $turmaId = $data->trm_id;
        $response = $this->repo->delete($turmaId);
        $this->assertEquals(1, $response);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
