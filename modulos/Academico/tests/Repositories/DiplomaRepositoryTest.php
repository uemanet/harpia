<?php

use Tests\ModulosTestCase;
use Modulos\Academico\Models\Diploma;
use Modulos\Seguranca\Models\Usuario;
use Modulos\Academico\Models\Registro;
use Stevebauman\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Academico\Repositories\DiplomaRepository;

class DiplomaRepositoryTest extends ModulosTestCase
{
    protected $user;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(Usuario::class)->create();
        $this->repo = $this->app->make(DiplomaRepository::class);
        $this->table = 'acd_diplomas';
    }

    private function mockUp()
    {
        // Aluno
        $pessoa = factory(\Modulos\Geral\Models\Pessoa::class)->create();

        $rg = factory(\Modulos\Geral\Models\Documento::class)->create([
            'doc_pes_id' => $pessoa->pes_id,
            'doc_tpd_id' => 1,
            'doc_conteudo' => 195819621970,
            'doc_orgao' => 'SSP'
        ]);

        $rg = factory(\Modulos\Geral\Models\Documento::class)->create([
            'doc_pes_id' => $pessoa->pes_id,
            'doc_tpd_id' => 2,
            'doc_conteudo' => 19942002,
            'doc_orgao' => 'SSP'
        ]);

        $aluno = factory(\Modulos\Academico\Models\Aluno::class)->create([
            'alu_pes_id' => $pessoa->pes_id
        ]);

        // Curso, Matriz e Modulos
        $curso = factory(Modulos\Academico\Models\Curso::class)->create();

        $matrizCurricular = factory(Modulos\Academico\Models\MatrizCurricular::class)->create([
            'mtc_crs_id' => $curso->crs_id
        ]);

        $moduloMatriz = factory(Modulos\Academico\Models\ModuloMatriz::class)->create([
            'mdo_mtc_id' => $matrizCurricular->mtc_id
        ]);

        $disciplinas = factory(Modulos\Academico\Models\Disciplina::class, 3)->create([
            'dis_nvc_id' => $curso->crs_nvc_id,
        ]);

        foreach ($disciplinas as $disciplina) {
            factory(\Modulos\Academico\Models\ModuloDisciplina::class)->create([
                'mdc_dis_id' => $disciplina->dis_id,
                'mdc_mdo_id' => $moduloMatriz->mdo_id,
                'mdc_tipo_disciplina' => 'obrigatoria',
            ]);
        }

        // Oferta de curso e matricula
        $ofertaCurso = factory(\Modulos\Academico\Models\OfertaCurso::class)->create([
            'ofc_crs_id' => $curso->crs_id,
            'ofc_mtc_id' => $matrizCurricular->mtc_id
        ]);

        // Com polos
        $polos[] = factory(\Modulos\Academico\Models\Polo::class)->create()->pol_id;
        $polos[] = factory(\Modulos\Academico\Models\Polo::class)->create()->pol_id;

        $ofertaCurso->polos()->sync($polos);

        $turma = factory(\Modulos\Academico\Models\Turma::class)->create([
            'trm_ofc_id' => $ofertaCurso->ofc_id
        ]);

        // Grupo
        $grupo = factory(\Modulos\Academico\Models\Grupo::class)->create([
            'grp_trm_id' => $turma->trm_id,
            'grp_pol_id' => $polos[0],
        ]);

        $matricula = factory(\Modulos\Academico\Models\Matricula::class)->create([
            'mat_alu_id' => $aluno->alu_id,
            'mat_trm_id' => $turma->trm_id,
            'mat_pol_id' => $polos[0],
            'mat_grp_id' => $grupo->grp_id,
        ]);

        return [$aluno, $matricula, $moduloMatriz];
    }

    private function seedTable()
    {
        $registros = [];
        $matriculas = [];

        $registroRepository = $this->app->make(\Modulos\Academico\Repositories\RegistroRepository::class);

        // Diplomas
        for ($i = 0; $i < 3; $i++) {
            list(, $matricula, ) = $this->mockUp();

            $matriculas[] = $matricula;
            $data = factory(Registro::class)->raw();
            $data = array_merge([
                'matricula' => $matricula->mat_id,
            ], $data);

            $registros[] = $registroRepository->create($data);
        }

        return [$matriculas, $registros];
    }

    /**
     * @expectedException \Exception
     */
    public function testCreate()
    {
        $this->expectException(\Exception::class);
        list(, $registros) = $this->seedTable();

        $this->expectException(\Exception::class);
        $data = factory(Diploma::class)->raw([
            'dip_reg_id' => $registros[0]->reg_id
        ]);
        $this->repo->create($data);
    }

    public function testFind()
    {
        $entry = factory(Diploma::class)->create();
        $id = $entry->dip_id;
        $fromRepository = $this->repo->find($id);

        $this->assertInstanceOf(Diploma::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepository->toArray());
        $this->assertEquals($entry->toArray(), $fromRepository->toArray());
    }

    public function testUpdate()
    {
        $entry = factory(Diploma::class)->create();
        $id = $entry->dip_id;

        $data = $entry->toArray();

        $data['dip_processo'] = "slug";

        $return = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(Diploma::class, $fromRepository);
        $this->assertEquals($data, $fromRepository->toArray());
    }

    public function testDelete()
    {
        $entry = factory(Diploma::class)->create();
        $id = $entry->dip_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        $entries = factory(Diploma::class, 2)->create();

        $model = new Diploma();
        $expected = $model->pluck('dip_processo', 'dip_id');
        $fromRepository = $this->repo->lists('dip_id', 'dip_processo');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        $entries = factory(Diploma::class, 2)->create();

        factory(Diploma::class)->create([
            'dip_processo' => '1564879'
        ]);

        $searchResult = $this->repo->search(array(['dip_processo', '=', '1564879']));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(Diploma::class, 2)->create();

        $entry = factory(Diploma::class)->create([
            'dip_processo' => "1564879"
        ]);

        $expected = [
            'dip_id' => $entry->dip_id,
            'dip_processo' => $entry->dip_processo
        ];

        $searchResult = $this->repo->search(array(['dip_processo', '=', "1564879"]), ['dip_id', 'dip_processo']);

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
        $created = factory(Diploma::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(Diploma::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new Diploma();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Diploma::class, 2)->create();

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
        $this->assertEquals(2, $response->first()->dip_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Diploma::class, 2)->create();
        factory(Diploma::class)->create([
            'dip_processo' => '1564879',
        ]);

        $search = [
            [
                'field' => 'dip_processo',
                'type' => '=',
                'term' => '1564879'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('1564879', $response->first()->dip_processo);
    }

    public function testPaginateWithSortAndSearch()
    {
        factory(Diploma::class, 2)->create();
        factory(Diploma::class, 2)->create([
            'dip_processo' => '1564879',
        ]);

        $sort = [
            'field' => 'dip_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'dip_processo',
                'type' => '=',
                'term' => '1564879'
            ]
        ];

        $response = $this->repo->paginate($sort, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());

        // Desc order
        $this->assertGreaterThan($response->last()->dip_id, $response->first()->dip_id);
        $this->assertEquals('1564879', $response->first()->dip_processo);
    }

    public function testPaginateRequest()
    {
        factory(Diploma::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'dip_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }

    public function testGetAlunosDiplomados()
    {
        $this->actingAs($this->user);
        list($matriculas, $registros) = $this->seedTable();

        foreach ($registros as $registro) {
            factory(Diploma::class)->create([
                'dip_reg_id' => $registro->reg_id
            ]);
        }

        $turma = $matriculas[0]->mat_trm_id;

        $diplomados = $this->repo->getAlunosDiplomados($turma);

        $this->assertEmpty($diplomados['aptos']);
        $this->assertNotEmpty($diplomados['diplomados']);
        $this->assertEquals(1, $diplomados['diplomados']->count());
    }

    public function testGetAlunosDiplomadosWithPolo()
    {
        $this->actingAs($this->user);
        list($matriculas) = $this->seedTable();

        $polo = $matriculas[0]->mat_pol_id;
        $turma = $matriculas[0]->mat_trm_id;

        $diplomados = $this->repo->getAlunosDiplomados($turma, $polo);

        $this->assertEmpty($diplomados['aptos']);
        $this->assertNotEmpty($diplomados['diplomados']);
        $this->assertEquals(1, $diplomados['diplomados']->count());

        // Com polo errado (nao deve trazer nenhum registro)
        $polo = $matriculas[1]->mat_pol_id;

        $diplomados = $this->repo->getAlunosDiplomados($turma, $polo);

        $this->assertEmpty($diplomados['aptos']);
        $this->assertEmpty($diplomados['diplomados']);
    }

    public function testGetPrintData()
    {
        $this->actingAs($this->user);
        list($matriculas) = $this->seedTable();

        $diploma = Diploma::all()->first();

        $id[] = $diploma->dip_id;

        $diplomados = $this->repo->getPrintData($id);

        $this->assertNotEmpty($diplomados);
    }

    public function testGetPrintDataReturnError()
    {
        $this->actingAs($this->user);
        list($matriculas) = $this->seedTable();

        $diploma = Diploma::all()->first();

        $id[] = $diploma->dip_id;
        $diplomados = $this->repo->getPrintData($id);

        $this->assertEquals('error', $diplomados['type']);
    }

    public function testGetPrintDataReturnNull()
    {
        $diplomados = $this->repo->getPrintData([1]);
        $this->assertEquals(null, $diplomados);
    }
}
