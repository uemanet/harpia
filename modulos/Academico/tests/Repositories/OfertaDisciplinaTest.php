<?php

use Tests\ModulosTestCase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modulos\Academico\Repositories\OfertaDisciplinaRepository;
use Modulos\Academico\Models\OfertaDisciplina;

class OfertaDisciplinaTest extends ModulosTestCase
{
    use DatabaseTransactions,
        WithoutMiddleware;

    protected $repo;

    public function setUp()
    {
        parent::setUp();
        $this->repo = $this->app->make(OfertaDisciplinaRepository::class);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testCreate()
    {
        $response = factory(OfertaDisciplina::class)->create();

        $data = $response->toArray();

        $this->assertInstanceOf(OfertaDisciplina::class, $response);

        $this->assertArrayHasKey('ofd_id', $data);
    }

    public function testfindAll()
    {
        $data = factory(OfertaDisciplina::class, 10)->create();

        $options = [
        'pes_nome' => $data[0]->professor->pessoa->pes_nome
      ];

        $select = ['pes_nome'];

        $order = ['pes_nome', 'desc'];

        $response = $this->repo->findAll($options, $select, $order);

        $this->assertNotEmpty($response);
    }

    public function testcountMatriculadosByOferta()
    {
        $data = factory(OfertaDisciplina::class)->create();
        $matriculasofertas = factory(\Modulos\Academico\Models\MatriculaOfertaDisciplina::class, 10)->create(['mof_ofd_id' => $data->ofd_id]);
        $response = $this->repo->countMatriculadosByOferta($data->ofd_id);

        $this->assertEquals($response, 10);
    }

    public function testverifyDisciplinaTurmaPeriodoReturnTrue()
    {
        $data = factory(OfertaDisciplina::class)->create();

        $response = $this->repo->verifyDisciplinaTurmaPeriodo($data->ofd_trm_id, $data->ofd_per_id, $data->ofd_mdc_id);

        $this->assertEquals($response, true);
    }

    public function testfindAllWithMapeamentoNotas()
    {
        $data = factory(\Modulos\Integracao\Models\MapeamentoNota::class)->create();

        $options = [
        'pes_nome' => $data->ofertadisciplina->professor->pessoa->pes_nome
      ];

        $select = ['pes_nome'];

        $order = ['pes_nome', 'desc'];

        $response = $this->repo->findAllWithMapeamentoNotas($options, $select, $order);

        $this->assertNotEmpty($response);
    }

    public function testverifyDisciplinaTurmaPeriodoReturnFalse()
    {
        $response = $this->repo->verifyDisciplinaTurmaPeriodo(1, 1, 1);

        $this->assertEquals($response, false);
    }

    public function testFind()
    {
        $data = factory(OfertaDisciplina::class)->create();
        $data = $data->toArray();

        unset($data['ofd_tipo_avaliacao']);

        $this->assertDatabaseHas('acd_ofertas_disciplinas', $data);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
