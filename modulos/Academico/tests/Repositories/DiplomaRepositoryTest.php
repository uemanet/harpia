<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modulos\Academico\Repositories\DiplomaRepository;
use Modulos\Academico\Models\Diploma;
use Modulos\Geral\Repositories\DocumentoRepository;
use Modulos\Geral\Models\Documento;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;

class DiplomaRepositoryTest extends TestCase
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

        $this->repo = $this->app->make(DiplomaRepository::class);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Modulos\Academico\Models\Diploma::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Diploma::class, 2)->create();

        $sort = [
            'field' => 'dip_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response[0]->dip_id);
    }

    public function testGetAlunosDiplomados()
    {
        $diploma = factory(Diploma::class, 2)->create();

        $diplomados = $this->repo->getAlunosDiplomados(3);

        $sort = [
            'field' => 'dip_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response[0]->dip_id);
    }

    public function testGetPrintData()
    {
        $diploma = factory(Diploma::class, 2)->create();

        $id = array(0 => $diploma[0]->dip_id );
        $idPessoa = $diploma[0]->matricula->aluno->pessoa->pes_id;

        $docRepository = new DocumentoRepository(new Documento());
        $documento = $docRepository->create(['doc_pes_id' => $idPessoa, 'doc_tpd_id' => 1, 'doc_conteudo' => 4653673163]);

        $diplomados = $this->repo->getPrintData($id);

        $sort = [
            'field' => 'dip_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response[0]->dip_id);
    }
}
