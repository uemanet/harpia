<?php

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Artisan;
use Modulos\Academico\Models\MatriculaOfertaDisciplina;

class MatriculaOfertaDisciplinaTeste extends TestCase
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

        $this->repo = $this->app->make(MatriculaOfertaDisciplina::class);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testCreate()
    {
        $response = factory(MatriculaOfertaDisciplina::class)->create();

        $data = $response->toArray();

        $this->assertInstanceOf(MatriculaOfertaDisciplina::class, $response);

        $this->assertArrayHasKey('mof_id', $data);
    }

    public function testeVerifyIfAlunoIsMatriculadoInDisciplinaOferecida()
    {
        $matricula = factory(Modulos\Academico\Models\Matricula::class)->create();

        $ofertaDisciplina = factory(Modulos\Academico\Models\OfertaDisciplina::class)->create();

        // matricular aluno nessa oferta
        $matriculaDisciplina = factory(MatriculaOfertaDisciplina::class)->create([
            'mof_mat_id' => $matricula->mat_id,
            'mof_ofd_id' => $ofertaDisciplina->ofd_id
        ]);

        $data = $matriculaDisciplina->toArray();

        $this->assertInstanceOf(MatriculaOfertaDisciplina::class, $matriculaDisciplina);

        $this->assertEquals($matricula->mat_id, $data['mof_mat_id']);
        $this->assertEquals($ofertaDisciplina->ofd_id, $data['mof_ofd_id']);
    }

    public function testDelete()
    {
        $data = factory(MatriculaOfertaDisciplina::class)->create();
        $matriculaOfertaDisciplinaId = $data->mof_id;

        $response = $this->repo->delete($matriculaOfertaDisciplinaId);

        $this->assertEquals(1, $response);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
