<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Models\Curso;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

class CursoRepositoryTest extends TestCase
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

        $this->user = factory(Modulos\Seguranca\Models\Usuario::class)->create();
        $this->repo = $this->app->make(CursoRepository::class);
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
        factory(\Modulos\Academico\Models\Vinculo::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(\Modulos\Academico\Models\Vinculo::class, 2)->create();

        $sort = [
            'field' => 'crs_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSearch()
    {
        factory(\Modulos\Academico\Models\Vinculo::class, 2)->create();

        $curso = factory(Curso::class)->create([
            'crs_nome' => 'eletrônica',
        ]);

        // Cria vinculo
        factory(\Modulos\Academico\Models\Vinculo::class)->create([
            'ucr_usr_id' => Auth::user()->usr_id,
            'ucr_crs_id' => $curso->crs_id
        ]);

        $search = [
            [
                'field' => 'crs_nome',
                'type' => 'like',
                'term' => 'eletrônica'
            ]
        ];

        $response = $this->repo->paginate(null, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertCount(1, $response);
    }

    public function testPaginateWithSearchAndOrder()
    {
        factory(\Modulos\Academico\Models\Vinculo::class, 2)->create();

        $sort = [
            'field' => 'crs_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'crs_id',
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
        factory(\Modulos\Academico\Models\Vinculo::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'crs_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testCreate()
    {
        $centro = factory(\Modulos\Academico\Models\Centro::class)->create();
        $nivel = factory(\Modulos\Academico\Models\NivelCurso::class)->create();
        $professor = factory(\Modulos\Academico\Models\Professor::class)->create();

        $data = [
          'crs_cen_id' => $centro->cen_id,
          'crs_nvc_id' => $nivel->nvc_id,
          'crs_prf_diretor' => $professor->prf_id,
          'crs_nome' => 'Curso de Teste',
          'crs_sigla' => 'CDT',
          'crs_data_autorizacao' => '06/12/2017',
          'crs_descricao' => 'Descrição do curso',
          'crs_resolucao' => 'resolução do curso',
          'crs_autorizacao' => 'autorização do curso',
          'crs_eixo' => 'eixo do curso',
          'crs_habilitacao' => 'habilitação do curso',
          'media_min_aprovacao' => '7',
          'media_min_final' => '5',
          'media_min_aprovacao_final' => '5',
          'modo_recuperacao' => 'substituir_menor_nota',
          'conceitos_aprovacao' => array("Bom", "Muito Bom" )
        ];

        $response = $this->repo->create($data);

        $this->assertEquals('success', $response['status']);
    }

    public function testCreateWithQueryException()
    {
        $centro = factory(\Modulos\Academico\Models\Centro::class)->create();
        $nivel = factory(\Modulos\Academico\Models\NivelCurso::class)->create();
        $professor = factory(\Modulos\Academico\Models\Professor::class)->create();
        //nível de curso colocado como Null para que a resposta venha com erro
        $data = [
          'crs_cen_id' => $centro->cen_id,
          'crs_nvc_id' => null,
          'crs_prf_diretor' => $professor->prf_id,
          'crs_nome' => 'Curso de Teste',
          'crs_sigla' => 'CDT',
          'crs_data_autorizacao' => '06/12/2017',
          'crs_descricao' => 'Descrição do curso',
          'crs_resolucao' => 'resolução do curso',
          'crs_autorizacao' => 'autorização do curso',
          'crs_eixo' => 'eixo do curso',
          'crs_habilitacao' => 'habilitação do curso',
          'media_min_aprovacao' => '7',
          'media_min_final' => '5',
          'media_min_aprovacao_final' => '5',
          'modo_recuperacao' => 'substituir_menor_nota',
          'conceitos_aprovacao' => array("Bom", "Muito Bom" )
        ];

        $response = $this->repo->create($data);

        $this->assertEquals('Erro ao criar curso. Parâmetros devem estar errados.', $response['message']);
    }

    public function testUpdate()
    {
        $curso = factory(\Modulos\Academico\Models\Curso::class)->create();
        $centro = factory(\Modulos\Academico\Models\Centro::class)->create();
        $nivel = factory(\Modulos\Academico\Models\NivelCurso::class)->create();
        $professor = factory(\Modulos\Academico\Models\Professor::class)->create();
        factory(\Modulos\Academico\Models\ConfiguracaoCurso::class)->create(['cfc_crs_id' => $curso->crs_id, 'cfc_nome' => 'media_min_aprovacao', 'cfc_valor' => 7]);
        factory(\Modulos\Academico\Models\ConfiguracaoCurso::class)->create(['cfc_crs_id' => $curso->crs_id, 'cfc_nome' => 'media_min_final', 'cfc_valor' => 7]);
        factory(\Modulos\Academico\Models\ConfiguracaoCurso::class)->create(['cfc_crs_id' => $curso->crs_id, 'cfc_nome' => 'media_min_aprovacao_final', 'cfc_valor' => 7]);
        factory(\Modulos\Academico\Models\ConfiguracaoCurso::class)->create(['cfc_crs_id' => $curso->crs_id, 'cfc_nome' => 'conceitos_aprovacao', 'cfc_valor' => '["Bom","Muito Bom","Excelente"]']);
        factory(\Modulos\Academico\Models\ConfiguracaoCurso::class)->create(['cfc_crs_id' => $curso->crs_id]);

        $data = [
          'crs_cen_id' => $centro->cen_id,
          'crs_nvc_id' => $nivel->nvc_id,
          'crs_prf_diretor' => $professor->prf_id,
          'crs_nome' => 'Curso de Teste',
          'crs_sigla' => 'CDT',
          'crs_data_autorizacao' => '06/12/2017',
          'crs_descricao' => 'Descrição do curso',
          'crs_resolucao' => 'resolução do curso',
          'crs_autorizacao' => 'autorização do curso',
          'crs_eixo' => 'eixo do curso',
          'crs_habilitacao' => 'habilitação do curso',
          'media_min_aprovacao' => '7',
          'media_min_final' => '5',
          'media_min_aprovacao_final' => '5',
          'modo_recuperacao' => 'substituir_menor_nota',
          'conceitos_aprovacao' => array("Bom", "Muito Bom" )
        ];

        $response = $this->repo->updateCurso($data, $curso->crs_id);

        $this->assertEquals('success', $response['status']);
    }

    public function testUpdateWithQueryException()
    {
        $curso = factory(\Modulos\Academico\Models\Curso::class)->create();
        $centro = factory(\Modulos\Academico\Models\Centro::class)->create();
        $nivel = factory(\Modulos\Academico\Models\NivelCurso::class)->create();
        $professor = factory(\Modulos\Academico\Models\Professor::class)->create();
        factory(\Modulos\Academico\Models\ConfiguracaoCurso::class)->create(['cfc_crs_id' => $curso->crs_id, 'cfc_nome' => 'media_min_aprovacao', 'cfc_valor' => 7]);
        factory(\Modulos\Academico\Models\ConfiguracaoCurso::class)->create(['cfc_crs_id' => $curso->crs_id, 'cfc_nome' => 'media_min_final', 'cfc_valor' => 7]);
        factory(\Modulos\Academico\Models\ConfiguracaoCurso::class)->create(['cfc_crs_id' => $curso->crs_id, 'cfc_nome' => 'media_min_aprovacao_final', 'cfc_valor' => 7]);
        factory(\Modulos\Academico\Models\ConfiguracaoCurso::class)->create(['cfc_crs_id' => $curso->crs_id, 'cfc_nome' => 'conceitos_aprovacao', 'cfc_valor' => '["Bom","Muito Bom","Excelente"]']);
        factory(\Modulos\Academico\Models\ConfiguracaoCurso::class)->create(['cfc_crs_id' => $curso->crs_id]);

        $data = [
          'crs_cen_id' => $centro->cen_id,
          'crs_nvc_id' => null,
          'crs_prf_diretor' => $professor->prf_id,
          'crs_nome' => 'Curso de Teste',
          'crs_sigla' => 'CDT',
          'crs_data_autorizacao' => '06/12/2017',
          'crs_descricao' => 'Descrição do curso',
          'crs_resolucao' => 'resolução do curso',
          'crs_autorizacao' => 'autorização do curso',
          'crs_eixo' => 'eixo do curso',
          'crs_habilitacao' => 'habilitação do curso',
          'media_min_aprovacao' => '7',
          'media_min_final' => '5',
          'media_min_aprovacao_final' => '5',
          'modo_recuperacao' => 'substituir_menor_nota',
          'conceitos_aprovacao' => array("Bom", "Muito Bom" )
        ];

        $response = $this->repo->updateCurso($data, $curso->crs_id);

        $this->assertEquals('Erro ao editar curso. Parâmetros devem estar errados.', $response['message']);
    }

    public function testFind()
    {
        $dados = factory(Curso::class)->create();

        $data = $dados->toArray();

        // Retorna para date format americano antes de comparar com o banco
        $data['crs_data_autorizacao'] = Carbon::createFromFormat('d/m/Y', $data['crs_data_autorizacao'])->toDateString();

        $this->assertDatabaseHas('acd_cursos', $data);
    }


    public function testDelete()
    {
        $data = factory(Curso::class)->create();
        $cursoId = $data->crs_id;

        $response = $this->repo->delete($cursoId);

        $this->assertInternalType('array', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('message', $response);
        $this->assertEquals($response['status'], 'success');
        $this->assertEquals($response['message'], 'Curso excluído com sucesso.');
    }

    public function testListsByCursoId()
    {
        $curso = factory(Curso::class)->create();
        $cursoId = $curso->crs_id;

        $response = $this->repo->listsByCursoId($cursoId);

        $this->assertNotEmpty($response, '');
    }


    public function testListsCursoByMatriz()
    {
        $matriz = factory(\Modulos\Academico\Models\MatrizCurricular::class)->create();

        $response = $this->repo->listsCursoByMatriz($matriz->mtc_id);

        $this->assertNotEmpty($response, '');
    }

    public function testListsCursosTecnicosWithVinculo()
    {
        $curso = factory(\Modulos\Academico\Models\Curso::class, 2)->create(['crs_nvc_id' => 2]);

        $vinculo = factory(\Modulos\Academico\Models\Vinculo::class)
                   ->create(['ucr_usr_id' => $this->user->usr_id,
                             'ucr_crs_id' => $curso[0]->crs_id]);
        $response = $this->repo->listsCursosTecnicos();

        $this->assertEquals(count($response), 1);
    }

    public function testListsCursosTecnicosWithoutVinculo()
    {
        $curso = factory(\Modulos\Academico\Models\Curso::class, 2)->create(['crs_nvc_id' => 2]);

        $response = $this->repo->listsCursosTecnicos(2, true);

        $this->assertNotEmpty($response, '');
    }

    public function testListsWithoutVinculo()
    {
        $curso = factory(\Modulos\Academico\Models\Curso::class, 20)->create();

        $response = $this->repo->lists('crs_id', 'crs_nome', true);

        $this->assertNotEmpty($response);
    }

    public function testListsWithVinculo()
    {
        $curso = factory(\Modulos\Academico\Models\Curso::class, 2)->create(['crs_nvc_id' => 2]);
        $vinculo = factory(\Modulos\Academico\Models\Vinculo::class)
                   ->create(['ucr_usr_id' => $this->user->usr_id,
                             'ucr_crs_id' => $curso[0]->crs_id]);

        $response = $this->repo->lists('crs_id', 'crs_nome', false);

        $this->assertNotEmpty($response);
    }

    public function testGetCursosPorNivel()
    {
        $curso = factory(\Modulos\Academico\Models\Curso::class, 2)->create();

        $response = $this->repo->getCursosPorNivel();

        $this->assertEmpty($response, '');
    }

    public function testGetCursosByAmbiente()
    {
        $turma = factory(\Modulos\Academico\Models\Turma::class)->create();
        $ambienteturma = factory(Modulos\Integracao\Models\AmbienteTurma::class)->create(['atr_trm_id' => $turma->trm_id]);

        $response = $this->repo->getCursosByAmbiente($ambienteturma->atr_trm_id);

        $this->assertNotEmpty($response, '');
    }

    public function testdeleteConfiguracoesReturnFalse()
    {
        $curso = factory(\Modulos\Academico\Models\Curso::class)->create();
        $response = $this->repo->deleteConfiguracoes($curso->crs_id);

        $this->assertEquals($response, false);
    }

    public function testdeleteConfiguracoesReturnTrue()
    {
        $curso = factory(\Modulos\Academico\Models\Curso::class)->create();
        factory(\Modulos\Academico\Models\ConfiguracaoCurso::class)->create(['cfc_crs_id' => $curso->crs_id, 'cfc_nome' => 'media_min_aprovacao', 'cfc_valor' => 7]);
        factory(\Modulos\Academico\Models\ConfiguracaoCurso::class)->create(['cfc_crs_id' => $curso->crs_id, 'cfc_nome' => 'media_min_final', 'cfc_valor' => 7]);
        factory(\Modulos\Academico\Models\ConfiguracaoCurso::class)->create(['cfc_crs_id' => $curso->crs_id, 'cfc_nome' => 'media_min_aprovacao_final', 'cfc_valor' => 7]);
        factory(\Modulos\Academico\Models\ConfiguracaoCurso::class)->create(['cfc_crs_id' => $curso->crs_id, 'cfc_nome' => 'conceitos_aprovacao', 'cfc_valor' => '["Bom","Muito Bom","Excelente"]']);
        factory(\Modulos\Academico\Models\ConfiguracaoCurso::class)->create(['cfc_crs_id' => $curso->crs_id]);

        $response = $this->repo->deleteConfiguracoes($curso->crs_id);

        $this->assertEquals($response, true);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
