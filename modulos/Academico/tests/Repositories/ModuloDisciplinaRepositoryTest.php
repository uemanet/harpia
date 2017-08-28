<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modulos\Academico\Repositories\ModuloDisciplinaRepository;
use Modulos\Academico\Models\ModuloDisciplina;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

use Modulos\Academico\Models\Curso;

class ModuloDisciplinaRepositoryTest extends TestCase
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

        $this->repo = $this->app->make(ModuloDisciplinaRepository::class);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }




    public function testCreate()
    {
        $response = factory(ModuloDisciplina::class)->create();

        $data = $response->toArray();

        $this->assertInstanceOf(ModuloDisciplina::class, $response);

        $this->assertArrayHasKey('mdc_id', $data);
    }

    public function testFind()
    {
        $dados = factory(ModuloDisciplina::class)->create();
        // Recupera id do curso a partir do Factory
        // Um Accessor é usado no model para retornar o nome do curso em vez de seu id
        $data = $dados->first()->toArray();
        $values = [
            'Obrigatória'=> 'obrigatoria',
            'Optativa' => 'optativa',
            'Eletiva' => 'eletiva',
            'TCC' => 'tcc'
        ];
        $data['mdc_tipo_disciplina'] = $values[$data['mdc_tipo_disciplina']];

        $this->seeInDatabase('acd_modulos_disciplinas', $data);
    }

    public function testGetDisciplinasPreRequisitos()
    {
        $modulodisciplina = factory(\Modulos\Academico\Models\ModuloDisciplina::class)->create();

        $response = $this->repo->getDisciplinasPreRequisitos($modulodisciplina->mdc_id);

        $this->assertEmpty($response, '');
    }

    public function testRepositoryCreate()
    {
        $modulodisciplina = factory(\Modulos\Academico\Models\ModuloDisciplina::class)->create();

        $modulo = factory(\Modulos\Academico\Models\ModuloMatriz::class)->create();
        $disciplina = factory(\Modulos\Academico\Models\Disciplina::class)->create();
        $arraydata =  [
          'dis_id' => $disciplina->dis_id,
          'tipo_disciplina' => 'obrigatoria',
          'mtc_id' => $modulo->matriz->mtc_id,
          'mod_id' => $modulo->mdo_id
        ];
        
        $response = $this->repo->create($arraydata);

        $this->assertEquals($response['type'], 'success');
    }

    public function testUpdate()
    {
        $data = factory(ModuloDisciplina::class)->create();

        $updateArray = $data->toArray();
        $updateArray['mdc_descricao'] = 'abcde_edcba';

        $modulodisciplinaId = $updateArray['mdc_id'];
        unset($updateArray['mdc_id']);

        $response = $this->repo->update($updateArray, $modulodisciplinaId, 'mdc_id');

        $this->assertEquals(1, $response);
    }

    public function testDelete()
    {
        $data = factory(ModuloDisciplina::class)->create();
        $modulodisciplinaId = $data->mdc_id;

        $response = $this->repo->delete($modulodisciplinaId);

        $this->assertEquals(1, $response);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
