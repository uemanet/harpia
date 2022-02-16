<?php
declare(strict_types=1);

use Tests\ModulosTestCase;
use Illuminate\Support\Facades\DB;
use Modulos\Academico\Models\Curso;
use Modulos\Academico\Models\Turma;
use Modulos\Seguranca\Models\Usuario;
use Modulos\Academico\Models\Vinculo;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;
use Modulos\Academico\Models\NivelCurso;
use Modulos\Academico\Models\MatrizCurricular;
use Uemanet\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Academico\Models\ConfiguracaoCurso;
use Modulos\Academico\Repositories\CursoRepository;

class CursoRepositoryTest extends ModulosTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->repo = $this->app->make(CursoRepository::class);
        $this->table = 'acd_cursos';
    }

    private function mockVinculo(int $qtdCursos = 1, $withBond = true, $curso = [])
    {
        // Usuario, Curso e Vinculo
        $user = factory(Usuario::class)->create();

        $cursos = factory(Curso::class, $qtdCursos)->create($curso);

        if ($withBond) {
            foreach ($cursos as $curso) {
                factory(Vinculo::class)->create([
                    'ucr_usr_id' => $user->usr_id,
                    'ucr_crs_id' => $curso->crs_id
                ]);
            }
        }

        return [$user, $cursos];
    }

    private function mockConfigs()
    {
        $modos = [
            'substituir_menor_nota',
            'substituir_media_final'
        ];

        return [
            'media_min_aprovacao' => 7,
            'media_min_final' => 5,
            'media_min_aprovacao_final' => 5,
            'modo_recuperacao' => $modos[random_int(0, 1)],
            'conceitos_aprovacao' => json_encode(['Bom', 'Muito Bom'], JSON_UNESCAPED_UNICODE)
        ];
    }

    public function testCreate()
    {
        // Necessario estar logado por conta do Vinculo
        $user = factory(Usuario::class)->create();
        $this->actingAs($user);

        $curso = factory(Curso::class)->raw();
        $configs = $this->mockConfigs();

        $data = array_merge($curso, $configs);

        $this->assertEquals(0, Curso::all()->count());
        $this->assertEquals(0, ConfiguracaoCurso::all()->count());
        $return = $this->repo->create($data);

        $this->assertTrue(is_array($return));
        $this->assertEquals(5, ConfiguracaoCurso::all()->count());
    }

    public function testCreateWithException()
    {
        config(['app.debug' => true]);
        // Necessario estar logado por conta do Vinculo
        $user = factory(Usuario::class)->create();
        $this->actingAs($user);

        $curso = factory(Curso::class)->raw();
        $configs = $this->mockConfigs();

        $data = array_merge($curso, $configs);

        $this->assertEquals(0, Curso::all()->count());
        $this->assertEquals(0, ConfiguracaoCurso::all()->count());

        // Exclui uma coluna para produzir um erro ao salvar
        Schema::table($this->table, function ($table) {
            $table->dropColumn('crs_descricao');
        });

        $this->expectException(\Exception::class);
        $return = $this->repo->create($data);

        $this->assertTrue(is_array($return));
        $this->assertArrayHasKey('status', $return);
        $this->assertEquals('error', $return['status']);
    }

    public function testFind()
    {
        $entry = factory(Curso::class)->create();
        $this->assertEquals(1, Curso::all()->count());
        $id = $entry->crs_id;
        $fromRepository = $this->repo->find($id);

        $entryData = $entry->toArray();
        $entryData['crs_data_autorizacao'] = $entry->getRawOriginal('crs_data_autorizacao');

        $data = $fromRepository->toArray();
        $data['crs_data_autorizacao'] = $fromRepository->getRawOriginal('crs_data_autorizacao');

        $this->assertInstanceOf(Curso::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertEquals($entryData, $data);
    }

    public function testUpdate()
    {
        $entry = factory(Curso::class)->create();
        $id = $entry->crs_id;

        $data = $entry->toArray();
        $data['crs_nome'] = "slug";

        $return = $this->repo->update($data, $id);
        $entry = Curso::find($id);
        $fromRepository = $this->repo->find($id);

        $entryData = $entry->toArray();
        $entryData['crs_data_autorizacao'] = $entry->getRawOriginal('crs_data_autorizacao');

        $data = $fromRepository->toArray();
        $data['crs_data_autorizacao'] = $fromRepository->getRawOriginal('crs_data_autorizacao');

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(Curso::class, $fromRepository);
        $this->assertEquals($entryData, $data);
    }

    public function testUpdateCurso()
    {
        // Necessario estar logado por conta do Vinculo
        $user = factory(Usuario::class)->create();
        $this->actingAs($user);

        $curso = factory(Curso::class)->raw();
        $configs = $this->mockConfigs();

        $data = array_merge($curso, $configs);

        $this->assertEquals(0, Curso::all()->count());
        $this->assertEquals(0, ConfiguracaoCurso::all()->count());
        $return = $this->repo->create($data);

        $entry = $this->repo->all()->first();
        $id = $entry->crs_id;

        $data = $entry->toArray();
        $data['crs_nome'] = "slug";

        $configs = $this->mockConfigs();
        $data = array_merge($data, $configs);

        // Update
        $return = $this->repo->updateCurso($data, $id);
        $entry = Curso::find($id);
        $fromRepository = $this->repo->find($id);

        $entryData = $entry->toArray();
        $entryData['crs_data_autorizacao'] = $entry->getRawOriginal('crs_data_autorizacao');

        $data = $fromRepository->toArray();
        $data['crs_data_autorizacao'] = $fromRepository->getRawOriginal('crs_data_autorizacao');

        $this->assertTrue(is_array($return));
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(Curso::class, $fromRepository);
        $this->assertEquals($entryData, $data);

        // Update - curso inexistente
        $return = $this->repo->updateCurso($data, 40);
        $this->assertTrue(is_array($return));
        $this->assertArrayHasKey('status', $return);
        $this->assertEquals('error', $return['status']);
        $this->assertEquals('Curso não existe.', $return['message']);

        // Update - erro / exception
        config(['app.debug' => true]);

        // Exclui uma coluna para produzir um erro ao atualizar
        Schema::table($this->table, function ($table) {
            $table->dropColumn('crs_descricao');
        });

        $this->expectException(\Exception::class);
        $data['crs_data_autorizacao'] = $fromRepository->crs_data_autorizacao;
        $return = $this->repo->updateCurso($data, $id);
        $this->assertTrue(is_array($return));
        $this->assertArrayHasKey('status', $return);
        $this->assertEquals('error', $return['status']);
        $this->assertEquals('Erro ao editar curso. Parâmetros devem estar errados.', $return['message']);
    }

    public function testDelete()
    {
        $user = factory(Usuario::class)->create();
        $this->actingAs($user);

        $curso = factory(Curso::class)->raw();
        $configs = $this->mockConfigs();

        $data = array_merge($curso, $configs);

        $this->repo->create($data);
        $entry = Curso::all()->first();
        $id = $entry->crs_id;

        $entryData = $entry->toArray();
        $entryData['crs_data_autorizacao'] = $entry->getRawOriginal('crs_data_autorizacao');
        $this->assertDatabaseHas($this->table, $entryData);

        $return = $this->repo->delete($id);

        $this->assertTrue(is_array($return));
        $this->assertArrayHasKey('status', $return);
        $this->assertEquals('success', $return['status']);
        $this->assertDatabaseMissing($this->table, $entry->toArray());

        // Curso sem configs
        $entry = factory(Curso::class)->create();
        $id = $entry->crs_id;

        $return = $this->repo->delete($id);
        $this->assertTrue(is_array($return));
        $this->assertArrayHasKey('status', $return);
        $this->assertEquals('success', $return['status']);
        $this->assertDatabaseMissing($this->table, $entry->toArray());

        // Excluir curso inexistente
        $return = $this->repo->delete(40);
        $this->assertTrue(is_array($return));
        $this->assertArrayHasKey('status', $return);
        $this->assertEquals('error', $return['status']);

        // Curso com vinculos
        list($user, $cursos) = $this->mockVinculo(1, true);
        $this->assertEquals(1, Vinculo::all()->count());

        $return = $this->repo->delete($cursos->first()->crs_id);

        $this->assertEquals(0, Vinculo::all()->count());
        $this->assertTrue(is_array($return));
        $this->assertArrayHasKey('status', $return);
        $this->assertEquals('success', $return['status']);
    }

    public function testLists()
    {
        $entries = factory(Curso::class, 2)->create();

        list($user, $cursos) = $this->mockVinculo(2);

        $this->actingAs($user);

        // Traz apenas os cursos com vinculo
        $fromRepository = $this->repo->lists('crs_id', 'crs_nome');
        $this->assertEquals($cursos->pluck('crs_nome', 'crs_id')->toArray(), $fromRepository);

        // Traz todos os cursos
        $model = new Curso();
        $expected = $model->pluck('crs_nome', 'crs_id');
        $fromRepository = $this->repo->lists('crs_id', 'crs_nome', true);

        $this->assertEquals($expected->toArray(), $fromRepository);
    }

    public function testSearch()
    {
        $entries = factory(Curso::class, 2)->create();

        factory(Curso::class)->create([
            'crs_nome' => 'centro'
        ]);

        $searchResult = $this->repo->search(array(['crs_nome', '=', 'centro']));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(Curso::class, 2)->create();

        $entry = factory(Curso::class)->create([
            'crs_nome' => "centro"
        ]);

        $expected = [
            'crs_id' => $entry->crs_id,
            'crs_nome' => $entry->crs_nome
        ];

        $searchResult = $this->repo->search(array(['crs_nome', '=', "centro"]), ['crs_id', 'crs_nome']);

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
        $this->assertEquals($expected, $searchResult->first()->toArray());
    }

    public function testAll()
    {
        // With empty database
        $collection = $this->repo->all();

        $this->assertEquals(0, $collection->count());

        // Non-empty database
        $created = factory(Curso::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(Curso::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new Curso();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Curso::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Curso::class, 2)->create();

        $sort = [
            'field' => 'crs_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->crs_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Curso::class, 2)->create();
        factory(Curso::class)->create([
            'crs_nome' => 'centro',
        ]);

        $search = [
            [
                'field' => 'crs_nome',
                'type' => '=',
                'term' => 'centro'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('centro', $response->first()->crs_nome);
    }

    public function testPaginateRequest()
    {
        factory(Curso::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'crs_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }

    public function testListsByCursoId()
    {
        factory(Curso::class, 2);
        $curso = factory(Curso::class)->create();
        $id = $curso->crs_id;

        $expected = $curso->pluck('crs_nome', 'crs_id')->toArray();
        $fromRepository = $this->repo->listsByCursoId($id);

        $this->assertEquals($expected, $fromRepository->toArray());
    }

    public function testListsByMatrizId()
    {
        factory(Curso::class, 2);
        $matriz = factory(MatrizCurricular::class)->create();
        $id = $matriz->mtc_id;

        $curso = Curso::find($matriz->mtc_crs_id);

        $expected = $curso->pluck('crs_nome', 'crs_id')->toArray();
        $fromRepository = $this->repo->listsByMatrizId($id);

        $this->assertEquals($expected, $fromRepository->toArray());
    }

    public function testGetCursosPorNivel()
    {
        $niveis = factory(NivelCurso::class, 3)->create();

        foreach ($niveis as $nivel) {
            factory(Curso::class)->create([
                'crs_nvc_id' => $nivel->nvc_id
            ]);
        }

        factory(Curso::class)->create([
            'crs_nvc_id' => $nivel->nvc_id
        ]);

        $result = $this->repo->getCursosPorNivel();

        $this->assertEquals(3, count($result));
        $this->assertEquals(1, $result[0]->quantidade);
        $this->assertEquals(1, $result[1]->quantidade);
        $this->assertEquals(2, $result[2]->quantidade);
    }

    public function testListsCursosTecnicos()
    {
        $niveis = factory(NivelCurso::class, 3)->create();

        foreach ($niveis as $nivel) {
            factory(Curso::class)->create([
                'crs_nvc_id' => $nivel->nvc_id
            ]);
        }

        // Nivel tecnico
        factory(Curso::class)->create([
            'crs_nvc_id' => 2
        ]);

        // Mock de vinculo e login
        list($user, $cursos) = $this->mockVinculo(2, true, ['crs_nvc_id' => 2]);
        $this->actingAs($user);

        // Todos os tecnicos
        $expected = Curso::where('crs_nvc_id', '=', 2)->pluck('crs_nome', 'crs_id')->toArray();
        $fromRepository = $this->repo->listsCursosTecnicos(2, true);

        $this->assertEquals($expected, $fromRepository);

        // Apenas com vinculos
        $expected = $cursos->pluck('crs_nome', 'crs_id')->toArray();
        $fromRepository = $this->repo->listsCursosTecnicos(2);

        $this->assertEquals($expected, $fromRepository);
    }

    public function testGetCursosByAmbiente()
    {
        // Mock de vinculo e login
        list($user, $cursos) = $this->mockVinculo(2, true, ['crs_nvc_id' => 2]);
        $this->actingAs($user);

        // Ofertas de cursos
        $ofertas = [];
        foreach ($cursos as $curso) {
            $ofertas[] = factory(Modulos\Academico\Models\OfertaCurso::class)->create([
                'ofc_crs_id' => $curso->crs_id
            ]);
        }

        // Turmas
        $turmas = [];
        foreach ($ofertas as $oferta) {
            $turmas[] = factory(Modulos\Academico\Models\Turma::class)->create([
                'trm_ofc_id' => $oferta->ofc_id
            ]);
        }

        // Vinculo da turma com ambiente
        $ambienteVirtual = factory(Modulos\Integracao\Models\AmbienteVirtual::class)->create();

        $ambienteId = $ambienteVirtual->amb_id;
        $ambienteTurmas = [];
        foreach ($turmas as $turma) {
            $ambienteTurmas[] = factory(Modulos\Integracao\Models\AmbienteTurma::class)->create([
                'atr_trm_id' => $turma->trm_id,
                'atr_amb_id' => $ambienteId
            ]);
        }

        $expected = $cursos->pluck('crs_nome', 'crs_id');

        $this->assertEquals($expected, $this->repo->getCursosByAmbiente($ambienteId));
    }
}
