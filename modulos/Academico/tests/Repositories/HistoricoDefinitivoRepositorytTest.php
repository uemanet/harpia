<?php

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Artisan;
use Modulos\Academico\Repositories\HistoricoDefinitivoRepository;
use Modulos\Geral\Repositories\DocumentoRepository;
use Carbon\Carbon;

class HistoricoDefinitivoRepositorytTest extends TestCase
{
    use DatabaseTransactions,
        WithoutMiddleware;

    protected $repo;
    protected $docrepo;

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

        $this->repo = $this->app->make(HistoricoDefinitivoRepository::class);
        $this->docrepo = $this->app->make(DocumentoRepository::class);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testGetGradeCurricularByMatricula()
    {
        $response = factory(\Modulos\Academico\Models\Matricula::class)->create();

        $rg = $this->docrepo->create(['doc_pes_id' => $response->aluno->pessoa->pes_id, 'doc_tpd_id' => 2, 'doc_conteudo' => '123456', 'doc_data_expedicao' => '10/10/2000']);
        $cpf = $this->docrepo->create(['doc_pes_id' => $response->aluno->pessoa->pes_id, 'doc_tpd_id' => 1, 'doc_conteudo' => '123456']);

        $response = $this->repo->getGradeCurricularByMatricula($response->mat_id);

        $this->assertNotEmpty($response, '');
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
