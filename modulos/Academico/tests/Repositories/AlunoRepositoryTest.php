<?php


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Artisan;
use Modulos\Academico\Models\Aluno;
use Modulos\Academico\Repositories\AlunoRepository;
use Modulos\Geral\Models\Pessoa;

class AlunoRepositoryTest extends TestCase
{
    use DatabaseTransactions,
        WithoutMiddleware;

    protected $repo;
    protected $user;

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

        $this->repo = $this->app->make(AlunoRepository::class);

        $this->user = factory(Modulos\Seguranca\Models\Usuario::class)->create();

        $this->actingAs($this->user);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Aluno::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Aluno::class, 2)->create();

        $sort = [
            'field' => 'alu_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response[0]->alu_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Aluno::class, 2)->create();

        $pessoa = factory(Pessoa::class)->create([
            'pes_nome' => 'Antonio',
        ]);

        factory(Aluno::class)->create([
            'alu_pes_id' => $pessoa->pes_id
        ]);

        $search = [
            [
                'field' => 'pes_nome',
                'type' => 'like',
                'term' => 'Antonio'
            ]
        ];

        $response = $this->repo->paginate(null, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertCount(1, $response);
    }

    public function testPaginateWithSearchAndOrder()
    {
        factory(Aluno::class, 2)->create();

        $sort = [
            'field' => 'alu_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'alu_id',
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
        factory(Aluno::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'alu_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }
    public function testPaginateRequestWithSearch()
    {
        $alunos = factory(Aluno::class, 2)->create();

        $cpfs = [];
        foreach ($alunos as $key => $aluno) {
            $cpfs[] = factory(Modulos\Geral\Models\Documento::class)->create(['doc_pes_id' => $aluno->pessoa->pes_id, 'doc_tpd_id' => 2]);
        }

        $requestParameters = [
            'pes_nome' => $alunos[0]->pessoa->pes_nome,
            'pes_cpf' => $cpfs[0]->doc_conteudo
        ];

        $response = $this->repo->paginateRequest($requestParameters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }
    public function testPaginateRequestOnlyBonds()
    {
        $alunos = factory(Aluno::class, 2)->create();
        $curso = factory(Modulos\Academico\Models\Curso::class)->create();
        $oferta = factory(Modulos\Academico\Models\OfertaCurso::class)->create([
          'ofc_crs_id' => $curso->crs_id
        ]);
        $turma = factory(Modulos\Academico\Models\Turma::class)->create([
          'trm_ofc_id' => $oferta->ofc_id
        ]);
        foreach ($alunos as $key => $aluno) {
            # code...
          factory(Modulos\Academico\Models\Matricula::class)->create([
            'mat_alu_id' => $aluno->alu_id,
            'mat_trm_id' => $turma->trm_id
          ]);
        }
        factory(Modulos\Academico\Models\Vinculo::class)->create([
          'ucr_crs_id' => $curso->crs_id,
          'ucr_usr_id' => $this->user->usr_id
        ]);


        $requestParameters = [
            'page' => '1',
            'field' => 'alu_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters, false, true);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response->total());
    }

    public function testCreate()
    {
        $response = factory(Aluno::class)->create();

        $data = $response->toArray();

        $this->assertInstanceOf(Aluno::class, $response);

        $this->assertArrayHasKey('alu_id', $data);
    }

    public function testFind()
    {
        $data = factory(Aluno::class)->create();

        $this->assertDatabaseHas('acd_alunos', $data->toArray());
    }

    public function testUpdate()
    {
        $data = factory(Aluno::class)->create();

        $updateArray = $data->toArray();
        $updateArray['alu_pes_id'] = 20;

        $alunoId = $updateArray['alu_id'];
        unset($updateArray['alu_id']);

        $response = $this->repo->update($updateArray, $alunoId, 'alu_id');

        $this->assertEquals(1, $response);
    }

    public function testDelete()
    {
        $data = factory(Aluno::class)->create();
        $alunoId = $data->alu_id;

        $response = $this->repo->delete($alunoId);

        $this->assertEquals(1, $response);
    }

    public function testgetCursosNotEmpty()
    {
        $data = factory(Modulos\Academico\Models\Matricula::class)->create();
        $alunoId = $data->aluno->alu_id;
        $cursoId = $data->turma->ofertacurso->curso->crs_id;

        $response = $this->repo->getCursos($alunoId);

        $this->assertEquals($cursoId, $response[0]);
    }

    public function testgetCursosEmpty()
    {
        $data = factory(Modulos\Academico\Models\Aluno::class)->create();
        $alunoId = $data->alu_id;
        $response = $this->repo->getCursos($alunoId);

        $this->assertEmpty($response);
    }

    public function testpaginateAllWithBonds()
    {
        factory(Aluno::class, 10)->create();

        $response = $this->repo->paginateAllWithBonds();

        $this->assertNotEmpty($response);
        $this->assertEquals(COUNT($response), 10);
    }

    public function testpaginateAllWithBondsWithSort()
    {
        factory(Aluno::class, 10)->create();
        $sort['field'] = 'pes_nome';
        $sort['sort'] = 'asc';
        $response = $this->repo->paginateAllWithBonds($sort);

        $this->assertNotEmpty($response);
        $this->assertEquals(COUNT($response), 10);
    }

    public function testpaginateAllWithBondsWithSearchByNome()
    {
        $alunos = factory(Aluno::class, 20)->create();
        $search[0]['field'] = 'pes_email';
        $search[0]['type'] = 'like';
        $search[0]['term'] = $alunos[0]->pessoa->pes_email;
        $response = $this->repo->paginateAllWithBonds(null, $search);

        $this->assertEquals(count($response), 1);
    }

    public function testpaginateAllWithBondsWithSearchAndSort()
    {
        $alunos = factory(Aluno::class, 20)->create();
        $search[0]['field'] = 'pes_email';
        $search[0]['type'] = 'like';
        $search[0]['term'] = $alunos[0]->pessoa->pes_email;
        $sort['field'] = 'pes_nome';
        $sort['sort'] = 'asc';

        $response = $this->repo->paginateAllWithBonds($sort, $search);

        $this->assertEquals(count($response), 1);
    }


    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
