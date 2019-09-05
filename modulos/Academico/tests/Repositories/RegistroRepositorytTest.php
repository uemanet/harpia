<?php

use Tests\ModulosTestCase;
use Modulos\Seguranca\Models\Usuario;
use Modulos\Academico\Models\Registro;
use Stevebauman\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Academico\Repositories\RegistroRepository;

class RegistroRepositorytTest extends ModulosTestCase
{
    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(Usuario::class)->create();
        $this->repo = $this->app->make(RegistroRepository::class);
        $this->table = 'acd_registros';
    }

    private function mockUp()
    {
        // Aluno
        $pessoa = factory(\Modulos\Geral\Models\Pessoa::class)->create([
            'pes_nome' => 'Irineu'
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

        $turma = factory(\Modulos\Academico\Models\Turma::class)->create([
            'trm_ofc_id' => $ofertaCurso->ofc_id
        ]);

        $matricula = factory(\Modulos\Academico\Models\Matricula::class)->create([
            'mat_alu_id' => $aluno->alu_id,
            'mat_trm_id' => $turma->trm_id,
        ]);

        return [$aluno, $matricula, $moduloMatriz];
    }

    private function seedTable()
    {
        $registros = [];
        $matriculas = [];

        // Certificado
        list(, $matricula, $modulo) = $this->mockUp();

        $matriculas[] = $matricula;
        $idModulo = $modulo->mdo_id;
        $idMatricula = $matricula->mat_id;

        $data = factory(Registro::class)->raw();
        $data = array_merge([
            'matricula' => $matricula->mat_id,
            'tipo_livro' => 'CERTIFICADO',
            'modulo' => $modulo->mdo_id
        ], $data);

        $registros[] = $this->repo->create($data);

        // Diploma
        list(, $matricula, $modulo) = $this->mockUp();
        $matriculas[] = $matricula;

        $data = factory(Registro::class)->raw();
        $data = array_merge([
            'matricula' => $matricula->mat_id,
        ], $data);

        $registros[] = $this->repo->create($data);

        return [$matriculas, $registros];
    }

    /**
     * Testa a criacao de registros para certificacao
     * 4 registros sao criados para verificar a corretude das regras de negocio para registros
     */
    public function testCreateCertificar()
    {
        $this->actingAs($this->user);

        // Reg 1
        list(, $matricula, $modulo) = $this->mockUp();
        $data = factory(Registro::class)->raw();

        $data = array_merge([
            'matricula' => $matricula->mat_id,
            'tipo_livro' => 'CERTIFICADO',
            'modulo' => $modulo->mdo_id
        ], $data);

        $entry = $this->repo->create($data);

        $this->assertInstanceOf(Registro::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->toArray());

        // Reg 2
        list(, $matricula, $modulo) = $this->mockUp();
        $data = factory(Registro::class)->raw();

        $data = array_merge([
            'matricula' => $matricula->mat_id,
            'tipo_livro' => 'CERTIFICADO',
            'modulo' => $modulo->mdo_id
        ], $data);

        $entry = $this->repo->create($data);

        $this->assertInstanceOf(Registro::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->toArray());

        // Reg 3
        list(, $matricula, $modulo) = $this->mockUp();
        $data = factory(Registro::class)->raw();

        $data = array_merge([
            'matricula' => $matricula->mat_id,
            'tipo_livro' => 'CERTIFICADO',
            'modulo' => $modulo->mdo_id
        ], $data);

        $entry = $this->repo->create($data);

        $this->assertInstanceOf(Registro::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->toArray());

        // Reg 4
        list(, $matricula, $modulo) = $this->mockUp();
        $data = factory(Registro::class)->raw();

        $data = array_merge([
            'matricula' => $matricula->mat_id,
            'tipo_livro' => 'CERTIFICADO',
            'modulo' => $modulo->mdo_id
        ], $data);

        $entry = $this->repo->create($data);

        $this->assertInstanceOf(Registro::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->toArray());


        // Verifica se as regras para folhas e registros estao sendo aplicadas na criacao
        $this->assertEquals(1, \Modulos\Academico\Models\Livro::all()->count());
        $this->assertEquals('CERTIFICADO', \Modulos\Academico\Models\Livro::all()->first()->liv_tipo_livro);

        $registros = Registro::all();

        // Folha
        $this->assertEquals(1, $registros->first()->reg_folha);
        $this->assertEquals(2, $registros->last()->reg_folha);

        // Registro
        $this->assertEquals($registros->first()->reg_registro, $registros->last()->reg_registro);
    }

    /**
     * Testa a criacao de registros para diplomacao
     * 4 registros sao criados para verificar a corretude das regras de negocio para registros
     */
    public function testCreateDiplomar()
    {
        $this->actingAs($this->user);

        // Reg 1
        list(, $matricula, $modulo) = $this->mockUp();
        $data = factory(Registro::class)->raw();

        $data = array_merge([
            'matricula' => $matricula->mat_id,
        ], $data);

        $entry = $this->repo->create($data);

        $this->assertInstanceOf(Registro::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->toArray());

        // Reg 2
        list(, $matricula, $modulo) = $this->mockUp();
        $data = factory(Registro::class)->raw();

        $data = array_merge([
            'matricula' => $matricula->mat_id,
        ], $data);

        $entry = $this->repo->create($data);

        $this->assertInstanceOf(Registro::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->toArray());

        // Reg 3
        list(, $matricula, $modulo) = $this->mockUp();
        $data = factory(Registro::class)->raw();

        $data = array_merge([
            'matricula' => $matricula->mat_id,
        ], $data);

        $entry = $this->repo->create($data);

        $this->assertInstanceOf(Registro::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->toArray());

        // Reg 4
        list(, $matricula, $modulo) = $this->mockUp();
        $data = factory(Registro::class)->raw();

        $data = array_merge([
            'matricula' => $matricula->mat_id,
        ], $data);

        $entry = $this->repo->create($data);

        $this->assertInstanceOf(Registro::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->toArray());


        // Verifica se as regras para folhas e registros estao sendo aplicadas na criacao
        $this->assertEquals(1, \Modulos\Academico\Models\Livro::all()->count());
        $this->assertEquals('DIPLOMA', \Modulos\Academico\Models\Livro::all()->first()->liv_tipo_livro);
        $registros = Registro::all();

        // Folha
        $this->assertEquals(1, $registros->first()->reg_folha);
        $this->assertEquals(2, $registros->last()->reg_folha);

        // Registro
        $this->assertEquals($registros->first()->reg_registro, $registros->last()->reg_registro);
    }

    public function testCreateNovoLivroCertificacao()
    {
        $this->actingAs($this->user);

        list(, $matricula, $modulo) = $this->mockUp();

        // Cria 600+ registros. O repositorio deve criar um novo livro para os registros excedentes
        for ($i = 0; $i <= 601; $i++) {
            $data = factory(Registro::class)->raw();

            $data = array_merge([
                'matricula' => random_int(1, 10000),
                'tipo_livro' => 'CERTIFICADO',
                'modulo' => $modulo->mdo_id
            ], $data);

            $entry = $this->repo->create($data);
        }

        $this->assertEquals(2, \Modulos\Academico\Models\Livro::all()->count());
        $this->assertEquals('CERTIFICADO', \Modulos\Academico\Models\Livro::all()->first()->liv_tipo_livro);
        $this->assertEquals('CERTIFICADO', \Modulos\Academico\Models\Livro::all()->last()->liv_tipo_livro);
    }

    public function testCreateNovoLivroDiplomacao()
    {
        $this->actingAs($this->user);

        list(, $matricula, $modulo) = $this->mockUp();

        // Cria 600+ registros. O repositorio deve criar um novo livro para os registros excedentes
        for ($i = 0; $i <= 601; $i++) {
            $data = factory(Registro::class)->raw();

            $data = array_merge([
                'matricula' => random_int(1, 10000),
            ], $data);

            $entry = $this->repo->create($data);
        }

        $this->assertEquals(2, \Modulos\Academico\Models\Livro::all()->count());
        $this->assertEquals('DIPLOMA', \Modulos\Academico\Models\Livro::all()->first()->liv_tipo_livro);
        $this->assertEquals('DIPLOMA', \Modulos\Academico\Models\Livro::all()->last()->liv_tipo_livro);
    }

    public function testFind()
    {
        $this->actingAs($this->user);

        factory(Registro::class, 10)->create();

        $entry = factory(Registro::class)->create();

        $id = $entry->reg_id;
        $fromRepository = $this->repo->find($id);

        $this->assertInstanceOf(Registro::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepository->toArray());
        $this->assertEquals($entry->toArray(), $fromRepository->toArray());
    }

    public function testFindBy()
    {
        factory(Registro::class, 10)->create();

        $entry = factory(Registro::class)->create([
            'reg_codigo_autenticidade' => '123654789'
        ]);

        $fromRepository = $this->repo->findBy([
            'reg_codigo_autenticidade' => '123654789'
        ]);

        // Find one
        $this->assertEquals(1, $fromRepository->count());
        $this->assertInstanceOf(TableCollection::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepository->first()->toArray());
        $this->assertEquals($entry->toArray(), $fromRepository->first()->toArray());

        // Find all
        $fromRepository = $this->repo->findBy();
        $this->assertEquals(11, $fromRepository->count());
    }

    public function testUpdate()
    {
        $entry = factory(Registro::class)->create();
        $id = $entry->reg_id;

        $data = $entry->toArray();

        $data['reg_registro'] = "slug";

        $return = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(Registro::class, $fromRepository);
        $this->assertEquals($data, $fromRepository->toArray());
    }

    public function testDelete()
    {
        $entry = factory(Registro::class)->create();
        $id = $entry->reg_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        $entries = factory(Registro::class, 2)->create();

        $model = new Registro();
        $expected = $model->pluck('reg_registro', 'reg_id');
        $fromRepository = $this->repo->lists('reg_id', 'reg_registro');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        $entries = factory(Registro::class, 2)->create();

        factory(Registro::class)->create([
            'reg_registro' => 'registro'
        ]);

        $searchResult = $this->repo->search(array(['reg_registro', '=', 'registro']));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(Registro::class, 2)->create();

        $entry = factory(Registro::class)->create([
            'reg_registro' => "registro"
        ]);

        $expected = [
            'reg_id' => $entry->reg_id,
            'reg_registro' => $entry->reg_registro
        ];

        $searchResult = $this->repo->search(array(['reg_registro', '=', "registro"]), ['reg_id', 'reg_registro']);

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
        $created = factory(Registro::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(Registro::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new Registro();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        $this->actingAs($this->user);

        list($matriculas) = $this->seedTable();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        $this->actingAs($this->user);

        list($matriculas) = $this->seedTable();

        factory(Registro::class, 2)->create();

        $sort = [
            'field' => 'reg_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->reg_id);
    }

    public function testPaginateWithSearch()
    {
        $this->actingAs($this->user);

        list($matriculas, $registro) = $this->seedTable();

        $expected = $registro[random_int(0, 1)]->reg_codigo_autenticidade;

        $search = [
            [
                'field' => 'reg_codigo_autenticidade',
                'type' => '=',
                'term' => $expected
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals($expected, $response->first()->reg_codigo_autenticidade);
    }

    public function testPaginateWithSortAndSearch()
    {
        $this->actingAs($this->user);

        list($matriculas, $registros) = $this->seedTable();

        // Mais um registro para a ultima matricula
        $data = factory(Registro::class)->raw([
            'reg_codigo_autenticidade' => '123456789',
        ]);
        $data = array_merge([
            'matricula' => $matriculas[1]->mat_id,
        ], $data);
        $this->repo->create($data);

        // Ordenacao + Busca ( Valida a chave pelo ultimo )
        $expected = $registros[0]->reg_codigo_autenticidade;

        $sort = [
            'field' => 'reg_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'pes_nome',
                'type' => 'like',
                'term' => 'Irineu'
            ]
        ];

        $response = $this->repo->paginate($sort, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertGreaterThan($response->last()->reg_id, $response->first()->reg_id);
        $this->assertEquals($expected, $response->last()->reg_codigo_autenticidade);
    }

    public function testPaginateRequest()
    {
        $this->actingAs($this->user);

        $this->seedTable();

        $requestParameters = [
            'page' => '1',
            'field' => 'reg_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }

    public function testMatriculaTemRegistro()
    {
        $this->actingAs($this->user);
        list(, $matricula, $modulo) = $this->mockUp();

        $idModulo = $modulo->mdo_id;
        $idMatricula = $matricula->mat_id;

        $this->assertFalse($this->repo->matriculaTemRegistro($idMatricula, $idModulo));

        $data = factory(Registro::class)->raw();
        $data = array_merge([
            'matricula' => $matricula->mat_id,
            'tipo_livro' => 'CERTIFICADO',
            'modulo' => $modulo->mdo_id

        ], $data);

        $this->repo->create($data);

        $this->assertTrue($this->repo->matriculaTemRegistro($idMatricula, $idModulo));
    }

    public function testDetalhesDoRegistro()
    {
        $this->actingAs($this->user);

        list(, $registros) = $this->seedTable();

        // Certificado
        $fromRepository = $this->repo->detalhesDoRegistro($registros[0]->reg_id);

        $this->assertInstanceOf(Registro::class, $fromRepository);
        $this->assertEquals($fromRepository->reg_codigo_autenticidade, $registros[0]->reg_codigo_autenticidade);

        // Diploma
        $fromRepository = $this->repo->detalhesDoRegistro($registros[1]->reg_id);

        $this->assertInstanceOf(Registro::class, $fromRepository);
        $this->assertEquals($fromRepository->reg_codigo_autenticidade, $registros[1]->reg_codigo_autenticidade);
    }
}
