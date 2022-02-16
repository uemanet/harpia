<?php

use Carbon\Carbon;
use Tests\ModulosTestCase;
use Tests\Helpers\Reflection;
use Modulos\Academico\Models\Curso;
use Modulos\Academico\Models\Vinculo;
use Modulos\Seguranca\Models\Usuario;
use Illuminate\Support\Facades\Schema;
use Modulos\Academico\Models\Registro;
use Modulos\Academico\Models\Matricula;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Eloquent\Collection;
use Uemanet\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Geral\Repositories\DocumentoRepository;
use Modulos\Academico\Repositories\RegistroRepository;
use Modulos\Academico\Repositories\MatriculaCursoRepository;

class MatriculaCursoRepositoryTest extends ModulosTestCase
{
    use Reflection;

    protected $repo;

    protected $regrepo;

    protected $docrepo;

    public function setUp(): void
    {
        parent::setUp();

        $this->repo = $this->app->make(MatriculaCursoRepository::class);
        $this->docrepo = $this->app->make(DocumentoRepository::class);
        $this->regrepo = $this->app->make(RegistroRepository::class);
        $this->table = 'acd_matriculas';
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

    private function mockCreateMatricula()
    {
        $curso = factory(Modulos\Academico\Models\Curso::class)->create([
            'crs_nome' => 'Curso 1',
            'crs_nvc_id' => 1,
        ]);

        $oferta = factory(Modulos\Academico\Models\OfertaCurso::class)->create([
            'ofc_crs_id' => $curso->crs_id
        ]);

        $turma = factory(Modulos\Academico\Models\Turma::class)->create(['trm_ofc_id' => $oferta->ofc_id]);
        $polo = factory(Modulos\Academico\Models\Polo::class)->create();
        $oferta->polos()->attach($polo->pol_id);

        $grupo = factory(Modulos\Academico\Models\Grupo::class)->create([
            'grp_trm_id' => $turma->trm_id,
            'grp_pol_id' => $polo->pol_id
        ]);

        $aluno = factory(Modulos\Academico\Models\Aluno::class)->create();

        $matricula = factory(Modulos\Academico\Models\Matricula::class)->create([
            'mat_alu_id' => $aluno->alu_id,
            'mat_trm_id' => $turma->trm_id,
            'mat_pol_id' => $polo->pol_id,
            'mat_grp_id' => $grupo->grp_id,
            'mat_situacao' => 'cursando',
            'mat_modo_entrada' => 'vestibular',
            'mat_data_conclusao' => '15/11/2015'
        ]);

        return [$curso, $oferta, $turma, $polo, $grupo, $aluno, $matricula];
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testCreate()
    {
        $response = factory(\Modulos\Academico\Models\Matricula::class)->create();

        $data = $response->toArray();

        $this->assertInstanceOf(\Modulos\Academico\Models\Matricula::class, $response);

        $this->assertArrayHasKey('mat_id', $data);
    }

    public function testFind()
    {
        $data = factory(\Modulos\Academico\Models\Matricula::class)->create();

        $data = $data->toArray();
        unset($data['mat_modo_entrada']);
        $data['mat_data_conclusao'] = Carbon::createFromFormat('d/m/Y', $data['mat_data_conclusao'])->toDateString();

        $this->assertDatabaseHas('acd_matriculas', $data);
    }

    public function testUpdate()
    {
        $entry = factory(\Modulos\Academico\Models\Matricula::class)->create();
        $id = $entry->mat_id;

        $data = $entry->toArray();
        $data['mat_modo_entrada'] = $entry->getRawOriginal('mat_modo_entrada');
        $data['mat_situacao'] = 'evadido';

        $return = $this->repo->update($data, $id);
        $data['mat_data_conclusao'] = $entry->getRawOriginal('mat_data_conclusao');

        $fromRepository = $this->repo->find($id);
        $fromRepositoryData = $fromRepository->toArray();
        $fromRepositoryData['mat_modo_entrada'] = $fromRepository->getRawOriginal('mat_modo_entrada');
        $fromRepositoryData['mat_data_conclusao'] = $fromRepository->getRawOriginal('mat_data_conclusao');

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(Matricula::class, $fromRepository);
        $this->assertEquals($data, $fromRepositoryData);
    }

    public function testDelete()
    {
        $entry = factory(\Modulos\Academico\Models\Matricula::class)->create();
        $id = $entry->mat_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testDeleteMatricula()
    {
        $entry = factory(\Modulos\Academico\Models\Matricula::class)->create(['mat_situacao' => 'cursando']);
        $return = $this->repo->deleteMatricula($entry->mat_id);
        $this->assertEquals('success', $return['type']);

        $entry = factory(\Modulos\Academico\Models\Matricula::class)->create(['mat_situacao' => 'reprovado']);
        $return = $this->repo->deleteMatricula($entry->mat_id);
        $this->assertEquals('error', $return['type']);

        $entry = factory(\Modulos\Academico\Models\Matricula::class)->create(['mat_situacao' => 'cursando']);
        $matriculaDisciplina = factory(\Modulos\Academico\Models\MatriculaOfertaDisciplina::class)->create(['mof_mat_id' => $entry->mat_id]);
        $return = $this->repo->deleteMatricula($entry->mat_id);
        $this->assertEquals('error', $return['type']);

        $return = $this->repo->deleteMatricula(100);
        $this->assertEquals('error', $return['type']);
    }

    public function testLists()
    {
        $entries = factory(\Modulos\Academico\Models\Matricula::class, 2)->create();

        $model = new Matricula();
        $expected = $model->pluck('mat_situacao', 'mat_id');
        $fromRepository = $this->repo->lists('mat_id', 'mat_situacao');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        $entries = factory(\Modulos\Academico\Models\Matricula::class, 2)->create();

        factory(\Modulos\Academico\Models\Matricula::class)->create([
            'mat_situacao' => 'desistente'
        ]);

        $searchResult = $this->repo->search(array(['mat_situacao', '=', 'desistente']));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(\Modulos\Academico\Models\Matricula::class, 2)->create();

        $entry = factory(\Modulos\Academico\Models\Matricula::class)->create([
            'mat_situacao' => "desistente"
        ]);

        $expected = [
            'mat_id' => $entry->mat_id,
            'mat_situacao' => $entry->mat_situacao
        ];

        $searchResult = $this->repo->search(array(['mat_situacao', '=', "desistente"]), ['mat_id', 'mat_situacao']);

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
        $created = factory(\Modulos\Academico\Models\Matricula::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(\Modulos\Academico\Models\Matricula::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new Matricula();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(\Modulos\Academico\Models\Matricula::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(\Modulos\Academico\Models\Matricula::class, 2)->create();

        $sort = [
            'field' => 'mat_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->mat_id);
    }

    public function testPaginateWithSearch()
    {
        factory(\Modulos\Academico\Models\Matricula::class, 2)->create();
        factory(\Modulos\Academico\Models\Matricula::class)->create([
            'mat_situacao' => 'desistente',
        ]);

        $search = [
            [
                'field' => 'mat_situacao',
                'type' => '=',
                'term' => 'desistente'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('desistente', $response->first()->mat_situacao);
    }

    public function testPaginateRequest()
    {
        factory(\Modulos\Academico\Models\Matricula::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'mat_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }

    public function testVerifyIfExistsMatriculaByOfertaCursoOrTurma()
    {
        $oferta = factory(Modulos\Academico\Models\OfertaCurso::class)->create();
        $turma = factory(Modulos\Academico\Models\Turma::class)->create(['trm_ofc_id' => $oferta->ofc_id]);
        $polo = factory(Modulos\Academico\Models\Polo::class)->create();
        $oferta->polos()->attach($polo->pol_id);

        $grupo = factory(Modulos\Academico\Models\Grupo::class)->create([
            'grp_trm_id' => $turma->trm_id,
            'grp_pol_id' => $polo->pol_id
        ]);

        // Sem matricula
        $aluno = factory(Modulos\Academico\Models\Aluno::class)->create();
        $result = $this->repo->verifyIfExistsMatriculaByOfertaCursoOrTurma($aluno->alu_id, $oferta->ofc_id, $turma->trm_id);

        $this->assertFalse($result);

        // Com matricula
        $aluno = factory(Modulos\Academico\Models\Aluno::class)->create();

        $matricula = factory(Modulos\Academico\Models\Matricula::class)->create([
            'mat_alu_id' => $aluno->alu_id,
            'mat_trm_id' => $turma->trm_id,
            'mat_pol_id' => $polo->pol_id,
            'mat_grp_id' => $grupo->grp_id,
            'mat_situacao' => 'cursando',
            'mat_modo_entrada' => 'vestibular',
            'mat_data_conclusao' => '15/11/2015'
        ]);

        $result = $this->repo->verifyIfExistsMatriculaByOfertaCursoOrTurma($aluno->alu_id, $oferta->ofc_id, $turma->trm_id);
        $this->assertTrue($result);
    }

    public function testVerifyIfExistsMatriculaByCursoAndSituacao()
    {
        $curso = factory(Modulos\Academico\Models\Curso::class)->create();

        $oferta = factory(Modulos\Academico\Models\OfertaCurso::class)->create([
            'ofc_crs_id' => $curso->crs_id
        ]);

        $turma = factory(Modulos\Academico\Models\Turma::class)->create(['trm_ofc_id' => $oferta->ofc_id]);
        $polo = factory(Modulos\Academico\Models\Polo::class)->create();
        $oferta->polos()->attach($polo->pol_id);

        $grupo = factory(Modulos\Academico\Models\Grupo::class)->create([
            'grp_trm_id' => $turma->trm_id,
            'grp_pol_id' => $polo->pol_id
        ]);

        $aluno = factory(Modulos\Academico\Models\Aluno::class)->create();

        $matricula = factory(Modulos\Academico\Models\Matricula::class)->create([
            'mat_alu_id' => $aluno->alu_id,
            'mat_trm_id' => $turma->trm_id,
            'mat_pol_id' => $polo->pol_id,
            'mat_grp_id' => $grupo->grp_id,
            'mat_situacao' => 'cursando',
            'mat_modo_entrada' => 'vestibular',
            'mat_data_conclusao' => '15/11/2015'
        ]);

        //Com status cursando
        $result = $this->repo->verifyIfExistsMatriculaByCursoAndSituacao($aluno->alu_id, $curso->crs_id);
        $this->assertTrue($result);

        $aluno = factory(Modulos\Academico\Models\Aluno::class)->create();

        $situacao = ['concluido', 'evadido', 'desistente', 'reprovado'];
        $matricula = factory(Modulos\Academico\Models\Matricula::class)->create([
            'mat_alu_id' => $aluno->alu_id,
            'mat_trm_id' => $turma->trm_id,
            'mat_pol_id' => $polo->pol_id,
            'mat_grp_id' => $grupo->grp_id,
            'mat_situacao' => $situacao[random_int(0, 3)],
            'mat_modo_entrada' => 'vestibular',
            'mat_data_conclusao' => '15/11/2015'
        ]);

        //Com status concluido, evadido, desistente, reprovado
        $result = $this->repo->verifyIfExistsMatriculaByCursoAndSituacao($aluno->alu_id, $curso->crs_id);
        $this->assertFalse($result);
    }

    public function testVerifyIfExistsMatriculaInCursoGraducao()
    {
        $curso = factory(Modulos\Academico\Models\Curso::class)->create([
            'crs_nvc_id' => 1,
        ]);

        $oferta = factory(Modulos\Academico\Models\OfertaCurso::class)->create([
            'ofc_crs_id' => $curso->crs_id
        ]);

        $turma = factory(Modulos\Academico\Models\Turma::class)->create(['trm_ofc_id' => $oferta->ofc_id]);
        $polo = factory(Modulos\Academico\Models\Polo::class)->create();
        $oferta->polos()->attach($polo->pol_id);

        $grupo = factory(Modulos\Academico\Models\Grupo::class)->create([
            'grp_trm_id' => $turma->trm_id,
            'grp_pol_id' => $polo->pol_id
        ]);

        $aluno = factory(Modulos\Academico\Models\Aluno::class)->create();

        $matricula = factory(Modulos\Academico\Models\Matricula::class)->create([
            'mat_alu_id' => $aluno->alu_id,
            'mat_trm_id' => $turma->trm_id,
            'mat_pol_id' => $polo->pol_id,
            'mat_grp_id' => $grupo->grp_id,
            'mat_situacao' => 'cursando',
            'mat_modo_entrada' => 'vestibular',
            'mat_data_conclusao' => '15/11/2015'
        ]);

        //Com status cursando
        $result = $this->repo->verifyIfExistsMatriculaInCursoGraducao($aluno->alu_id);
        $this->assertTrue($result);

        $aluno = factory(Modulos\Academico\Models\Aluno::class)->create();

        $situacao = ['concluido', 'evadido', 'desistente'];
        $matricula = factory(Modulos\Academico\Models\Matricula::class)->create([
            'mat_alu_id' => $aluno->alu_id,
            'mat_trm_id' => $turma->trm_id,
            'mat_pol_id' => $polo->pol_id,
            'mat_grp_id' => $grupo->grp_id,
            'mat_situacao' => $situacao[random_int(0, 2)],
            'mat_modo_entrada' => 'vestibular',
            'mat_data_conclusao' => '15/11/2015'
        ]);

        //Com status concluido, evadido, desistente
        $result = $this->repo->verifyIfExistsMatriculaInCursoGraducao($aluno->alu_id);
        $this->assertFalse($result);
    }

    public function testVerifyExistsVagasByTurma()
    {
        $curso = factory(Modulos\Academico\Models\Curso::class)->create([
            'crs_nvc_id' => 1,
        ]);

        $oferta = factory(Modulos\Academico\Models\OfertaCurso::class)->create([
            'ofc_crs_id' => $curso->crs_id
        ]);

        $turma = factory(Modulos\Academico\Models\Turma::class)->create([
            'trm_ofc_id' => $oferta->ofc_id,
            'trm_qtd_vagas' => 2
        ]);
        $polo = factory(Modulos\Academico\Models\Polo::class)->create();
        $oferta->polos()->attach($polo->pol_id);

        $grupo = factory(Modulos\Academico\Models\Grupo::class)->create([
            'grp_trm_id' => $turma->trm_id,
            'grp_pol_id' => $polo->pol_id
        ]);

        $aluno = factory(Modulos\Academico\Models\Aluno::class)->create();

        $matricula = factory(Modulos\Academico\Models\Matricula::class)->create([
            'mat_alu_id' => $aluno->alu_id,
            'mat_trm_id' => $turma->trm_id,
            'mat_pol_id' => $polo->pol_id,
            'mat_grp_id' => $grupo->grp_id,
            'mat_situacao' => 'cursando',
            'mat_modo_entrada' => 'vestibular',
            'mat_data_conclusao' => '15/11/2015'
        ]);

        //Turma com vagas
        $result = $this->repo->verifyExistsVagasByTurma($turma->trm_id);
        $this->assertTrue($result);

        $turma = factory(Modulos\Academico\Models\Turma::class)->create([
            'trm_ofc_id' => $oferta->ofc_id,
            'trm_qtd_vagas' => 1
        ]);

        $aluno = factory(Modulos\Academico\Models\Aluno::class)->create();

        $matricula = factory(Modulos\Academico\Models\Matricula::class)->create([
            'mat_alu_id' => $aluno->alu_id,
            'mat_trm_id' => $turma->trm_id,
            'mat_pol_id' => $polo->pol_id,
            'mat_grp_id' => $grupo->grp_id,
            'mat_situacao' => 'cursando',
            'mat_modo_entrada' => 'vestibular',
            'mat_data_conclusao' => '15/11/2015'
        ]);

        //Turma sem vagas
        $result = $this->repo->verifyExistsVagasByTurma($turma->trm_id);
        $this->assertFalse($result);
    }

    public function testListsCursosNotMatriculadoByAluno()
    {
        $aluno = factory(Modulos\Academico\Models\Aluno::class)->create();

        //Sem curso cadastrado
        $result = $this->repo->listsCursosNotMatriculadoByAluno($aluno->alu_id);
        $this->assertEmpty($result);

        $curso = factory(Modulos\Academico\Models\Curso::class)->create([
            'crs_nome' => 'Curso 1',
            'crs_nvc_id' => 1,
        ]);

        $oferta = factory(Modulos\Academico\Models\OfertaCurso::class)->create([
            'ofc_crs_id' => $curso->crs_id
        ]);

        $turma = factory(Modulos\Academico\Models\Turma::class)->create(['trm_ofc_id' => $oferta->ofc_id]);
        $polo = factory(Modulos\Academico\Models\Polo::class)->create();
        $oferta->polos()->attach($polo->pol_id);

        $grupo = factory(Modulos\Academico\Models\Grupo::class)->create([
            'grp_trm_id' => $turma->trm_id,
            'grp_pol_id' => $polo->pol_id
        ]);

        $aluno = factory(Modulos\Academico\Models\Aluno::class)->create();

        //Sem matricula
        $result = $this->repo->listsCursosNotMatriculadoByAluno($aluno->alu_id);
        $this->assertContains('Curso 1', $result);

        $aluno = factory(Modulos\Academico\Models\Aluno::class)->create();
        $matricula = factory(Modulos\Academico\Models\Matricula::class)->create([
            'mat_alu_id' => $aluno->alu_id,
            'mat_trm_id' => $turma->trm_id,
            'mat_pol_id' => $polo->pol_id,
            'mat_grp_id' => $grupo->grp_id,
            'mat_situacao' => 'cursando',
            'mat_modo_entrada' => 'vestibular',
            'mat_data_conclusao' => '15/11/2015'
        ]);

        //Com matricula
        $result = $this->repo->listsCursosNotMatriculadoByAluno($aluno->alu_id);
        $this->assertNotContains('Curso 1', $result);
    }

    public function testFindAll()
    {
        $curso = factory(Modulos\Academico\Models\Curso::class)->create([
            'crs_nome' => 'Curso 1',
            'crs_nvc_id' => 1,
        ]);

        $oferta = factory(Modulos\Academico\Models\OfertaCurso::class)->create([
            'ofc_crs_id' => $curso->crs_id
        ]);

        $turma = factory(Modulos\Academico\Models\Turma::class)->create(['trm_ofc_id' => $oferta->ofc_id]);
        $polo = factory(Modulos\Academico\Models\Polo::class)->create();
        $oferta->polos()->attach($polo->pol_id);

        $grupo = factory(Modulos\Academico\Models\Grupo::class)->create([
            'grp_trm_id' => $turma->trm_id,
            'grp_pol_id' => $polo->pol_id
        ]);

        $aluno = factory(Modulos\Academico\Models\Aluno::class)->create();

        $matricula = factory(Modulos\Academico\Models\Matricula::class)->create([
            'mat_alu_id' => $aluno->alu_id,
            'mat_trm_id' => $turma->trm_id,
            'mat_pol_id' => $polo->pol_id,
            'mat_grp_id' => $grupo->grp_id,
            'mat_situacao' => 'cursando',
            'mat_modo_entrada' => 'vestibular',
            'mat_data_conclusao' => '15/11/2015'
        ]);

        //Com situacao de matricula cursando
        $result = $this->repo->findAll(['mat_situacao' => 'cursando'], null, ['pes_nome' => 'asc']);
        $this->assertNotEmpty($result);

        //Com situacao de matricula cursando e com select
        $result = $this->repo->findAll(['mat_situacao' => 'cursando'], ['crs_nome'], ['pes_nome' => 'asc']);
        $this->assertContains(['crs_nome' => 'Curso 1'], $result->toArray());

        //Com situacao de matricula reprovado
        $result = $this->repo->findAll(['mat_situacao' => 'reprovado'], null, ['pes_nome' => 'asc']);
        $this->assertEmpty($result);
    }

    public function testFindAllVinculo()
    {
        // Mock de vinculo e login
        list($user, $cursos) = $this->mockVinculo(1, true, ['crs_nome' => 'Curso 1', 'crs_nvc_id' => 1, ]);
        $this->actingAs($user);

        $oferta = factory(Modulos\Academico\Models\OfertaCurso::class)->create([
            'ofc_crs_id' => $cursos->first()->crs_id
        ]);

        $turma = factory(Modulos\Academico\Models\Turma::class)->create(['trm_ofc_id' => $oferta->ofc_id]);
        $polo = factory(Modulos\Academico\Models\Polo::class)->create();
        $oferta->polos()->attach($polo->pol_id);

        $grupo = factory(Modulos\Academico\Models\Grupo::class)->create([
            'grp_trm_id' => $turma->trm_id,
            'grp_pol_id' => $polo->pol_id
        ]);

        $aluno = factory(Modulos\Academico\Models\Aluno::class)->create();

        $matricula = factory(Modulos\Academico\Models\Matricula::class)->create([
            'mat_alu_id' => $aluno->alu_id,
            'mat_trm_id' => $turma->trm_id,
            'mat_pol_id' => $polo->pol_id,
            'mat_grp_id' => $grupo->grp_id,
            'mat_situacao' => 'cursando',
            'mat_modo_entrada' => 'vestibular',
            'mat_data_conclusao' => '15/11/2015'
        ]);

        //Sem select
        $result = $this->repo->findAllVinculo(['mat_alu_id' => $aluno->alu_id, 'mat_situacao' => 'cursando']);
        $this->assertNotEmpty($result);

        //Com Select e OrderBy
        $result = $this->repo->findAllVinculo(['mat_alu_id' => $aluno->alu_id, 'mat_situacao' => 'cursando'], ['crs_nome'], ['crs_nome' => 'asc']);
        $this->assertContains(['crs_nome' => 'Curso 1'], $result->toArray());

        //Sem matricula
        $aluno = factory(Modulos\Academico\Models\Aluno::class)->create();
        $result = $this->repo->findAllVinculo(['mat_alu_id' => $aluno->alu_id, 'mat_situacao' => 'cursando'], ['crs_nome'], ['crs_nome' => 'asc']);
        $this->assertEmpty($result);
    }

    public function testCreateMatricula()
    {
        $data = $this->mockCreateMatricula();
        list($curso, $oferta, $turma, $polo, $grupo, $aluno) = $data;

        $options = [
            'ofc_id' => $oferta->ofc_id,
            'crs_id' => $curso->crs_id,
            'mat_trm_id' => $turma->trm_id,
            'mat_pol_id' => $polo->pol_id,
            'mat_grp_id' => $grupo->grp_id,
            'mat_modo_entrada' => 'vestibular',
        ];

        $result = $this->repo->createMatricula($aluno->alu_id, $options);
        $this->assertEquals('error', $result['type']);

        $oferta = factory(Modulos\Academico\Models\OfertaCurso::class)->create([
            'ofc_crs_id' => $curso->crs_id
        ]);

        $turma = factory(Modulos\Academico\Models\Turma::class)->create(['trm_ofc_id' => $oferta->ofc_id]);
        $polo = factory(Modulos\Academico\Models\Polo::class)->create();
        $oferta->polos()->attach($polo->pol_id);

        $grupo = factory(Modulos\Academico\Models\Grupo::class)->create([
            'grp_trm_id' => $turma->trm_id,
            'grp_pol_id' => $polo->pol_id
        ]);

        $aluno = factory(Modulos\Academico\Models\Aluno::class)->create();
        $matricula = factory(Modulos\Academico\Models\Matricula::class)->create([
            'mat_alu_id' => $aluno->alu_id,
            'mat_trm_id' => $turma->trm_id,
            'mat_pol_id' => $polo->pol_id,
            'mat_grp_id' => $grupo->grp_id,
            'mat_situacao' => 'cursando',
            'mat_modo_entrada' => 'vestibular',
            'mat_data_conclusao' => '15/11/2015'
        ]);

        $result = $this->repo->createMatricula($aluno->alu_id, $options);
        $this->assertEquals('error', $result['type']);

        $data = $this->mockCreateMatricula();
        list(, , , , , $aluno) = $data;

        $result = $this->repo->createMatricula($aluno->alu_id, $options);
        $this->assertEquals('error', $result['type']);

        $curso = factory(Modulos\Academico\Models\Curso::class)->create([
            'crs_nome' => 'Curso 1',
            'crs_nvc_id' => 1,
        ]);

        $oferta = factory(Modulos\Academico\Models\OfertaCurso::class)->create([
            'ofc_crs_id' => $curso->crs_id
        ]);

        $turma = factory(Modulos\Academico\Models\Turma::class)->create([
            'trm_ofc_id' => $oferta->ofc_id,
            'trm_qtd_vagas' => 0
        ]);
        $polo = factory(Modulos\Academico\Models\Polo::class)->create();
        $oferta->polos()->attach($polo->pol_id);

        $grupo = factory(Modulos\Academico\Models\Grupo::class)->create([
            'grp_trm_id' => $turma->trm_id,
            'grp_pol_id' => $polo->pol_id
        ]);

        $aluno = factory(Modulos\Academico\Models\Aluno::class)->create();

        $options = [
            'ofc_id' => $oferta->ofc_id,
            'crs_id' => $curso->crs_id,
            'mat_trm_id' => $turma->trm_id,
            'mat_pol_id' => $polo->pol_id,
            'mat_grp_id' => $grupo->grp_id,
            'mat_modo_entrada' => 'vestibular',
        ];

        $result = $this->repo->createMatricula($aluno->alu_id, $options);
        $this->assertEquals('error', $result['type']);

        $data = $this->mockCreateMatricula();
        list($curso, $oferta, $turma, $polo, $grupo, ) = $data;

        $options = [
            'ofc_id' => $oferta->ofc_id,
            'crs_id' => $curso->crs_id,
            'mat_trm_id' => $turma->trm_id,
            'mat_pol_id' => $polo->pol_id,
            'mat_grp_id' => $grupo->grp_id,
            'mat_modo_entrada' => 'vestibular',
        ];

        $aluno = factory(Modulos\Academico\Models\Aluno::class)->create();
        $result = $this->repo->createMatricula($aluno->alu_id, $options);
        $this->assertEquals('success', $result['type']);

        $options = [
            'ofc_id' => $oferta->ofc_id,
            'crs_id' => $curso->crs_id,
            'mat_trm_id' => $turma->trm_id,
            'mat_pol_id' => $polo->pol_id,
            'mat_grp_id' => $grupo->grp_id,
            'mat_modo_entrada' => 'vestibular',
        ];


        Schema::table('acd_matriculas', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->dropColumn('mat_modo_entrada');
        });

        $aluno = factory(Modulos\Academico\Models\Aluno::class)->create();
        $result = $this->repo->createMatricula($aluno->alu_id, $options);
        $this->assertEquals('error', $result['type']);

        $result = $this->repo->createMatricula($aluno->alu_id, $options);
        $this->assertEquals('error', $result['type']);
    }

    public function testFindMatriculaIdByTurmaAluno()
    {
        $data = $this->mockCreateMatricula();
        list($curso, $oferta, $turma, $polo, $grupo, $aluno) = $data;

        //Sem matricula em TCC
        $result = $this->repo->findMatriculaIdByTurmaAluno($aluno->alu_id, $turma->trm_id);
        $this->assertNull($result);

        $matrizCurricular = factory(Modulos\Academico\Models\MatrizCurricular::class)->create([
            'mtc_crs_id' => $curso->crs_id
        ]);

        $oferta = factory(Modulos\Academico\Models\OfertaCurso::class)->create([
            'ofc_crs_id' => $curso->crs_id,
            'ofc_mtc_id' => $matrizCurricular->mtc_id,
        ]);

        $turma = factory(Modulos\Academico\Models\Turma::class)->create([
            'trm_ofc_id' => $oferta->ofc_id,
        ]);

        $polo = factory(Modulos\Academico\Models\Polo::class)->create();
        $oferta->polos()->attach($polo->pol_id);

        $grupo = factory(Modulos\Academico\Models\Grupo::class)->create([
            'grp_trm_id' => $turma->trm_id,
            'grp_pol_id' => $polo->pol_id
        ]);

        $moduloMatriz = factory(Modulos\Academico\Models\ModuloMatriz::class)->create([
            'mdo_mtc_id' => $matrizCurricular->mtc_id
        ]);

        $disciplina = factory(Modulos\Academico\Models\Disciplina::class)->create([
            'dis_nvc_id' => $curso->crs_nvc_id
        ]);

        $moduloDisciplina = factory(Modulos\Academico\Models\ModuloDisciplina::class)->create([
            'mdc_dis_id' => $disciplina->dis_id,
            'mdc_mdo_id' => $moduloMatriz->mdo_id,
            'mdc_tipo_disciplina' => 'tcc'
        ]);

        $professor = factory(Modulos\Academico\Models\Professor::class)->create();

        $ofertaDisciplina = factory(Modulos\Academico\Models\OfertaDisciplina::class)->create([
            'ofd_mdc_id' => $moduloDisciplina->mdc_id,
            'ofd_trm_id' => $turma->trm_id,
            'ofd_per_id' => $turma->trm_per_id,
            'ofd_prf_id' => $professor->prf_id,
            'ofd_tipo_avaliacao' => 'numerica',
            'ofd_qtd_vagas' => 500
        ]);

        factory(\Modulos\Geral\Models\Titulacao::class, 7)->create();

        $titulacaoProfessor = factory(Modulos\Geral\Models\TitulacaoInformacao::class)->create([
            'tin_pes_id' => $professor->pessoa->pes_id,
            'tin_tit_id' => random_int(2, 7),
        ]);

        $aluno = factory(Modulos\Academico\Models\Aluno::class)->create();

        $matricula = factory(Modulos\Academico\Models\Matricula::class)->create([
            'mat_alu_id' => $aluno->alu_id,
            'mat_trm_id' => $turma->trm_id,
            'mat_pol_id' => $polo->pol_id,
            'mat_grp_id' => $grupo->grp_id,
            'mat_situacao' => 'cursando',
            'mat_modo_entrada' => 'vestibular',
            'mat_data_conclusao' => '15/11/2015'
        ]);

        $matriculaOfertaDisciplina = factory(Modulos\Academico\Models\MatriculaOfertaDisciplina::class)->create([
            'mof_mat_id' => $matricula->mat_id,
            'mof_ofd_id' => $ofertaDisciplina->ofd_id,
            'mof_tipo_matricula' => 'matriculacomum',
            'mof_situacao_matricula' => 'aprovado_media'
        ]);

        //Com matricula em TCC
        $result = $this->repo->findMatriculaIdByTurmaAluno($aluno->alu_id, $turma->trm_id);
        $this->assertNotEmpty($result);
    }

    public function testFindDadosByTurmaId()
    {
        $data = $this->mockMatriculaGeral(['tcc']);
        list($turma) = $data;

        //Com matricula em TCC
        $result = $this->repo->findDadosByTurmaId($turma->trm_id);
        $this->assertNotEmpty($result);


        $data = $this->mockCreateMatricula();
        list($turma) = $data;

        //Sem matricula em TCC
        $result = $this->repo->findDadosByTurmaId($turma->trm_id);
        $this->assertEmpty($result);
    }

    public function testGetAlunosAptosOrNot()
    {
        $curso = factory(Modulos\Academico\Models\Curso::class)->create([
            'crs_nome' => 'Curso 1',
            'crs_nvc_id' => 1,
        ]);

        $matrizCurricular = factory(Modulos\Academico\Models\MatrizCurricular::class)->create([
            'mtc_crs_id' => $curso->crs_id
        ]);

        $oferta = factory(Modulos\Academico\Models\OfertaCurso::class)->create([
            'ofc_crs_id' => $curso->crs_id,
            'ofc_mtc_id' => $matrizCurricular->mtc_id,
        ]);

        $turma = factory(Modulos\Academico\Models\Turma::class)->create([
            'trm_ofc_id' => $oferta->ofc_id,
        ]);

        $polo = factory(Modulos\Academico\Models\Polo::class)->create();
        $oferta->polos()->attach($polo->pol_id);

        //Turma e Polo sem matriculas cadastradas
        $result = $this->repo->getAlunosAptosOrNot($turma->trm_id, $polo->pol_id);
        $this->assertEmpty($result);

        $data = $this->mockMatriculaGeral(['tcc'], 'concluido', 'cursando', 4);
        list($turma, $polo) = $data;

        //Situacao Concluido para 4 alunos
        $result = $this->repo->getAlunosAptosOrNot($turma->trm_id, $polo->pol_id);
        foreach ($result as $item) {
            $this->assertContains('Concluído', $item->status);
        }

        $data = $this->mockMatriculaGeral(['tcc'], 'reprovado', 'cursando', 4);
        list($turma, $polo) = $data;

        //Situacao Reprovado para 4 alunos
        $result = $this->repo->getAlunosAptosOrNot($turma->trm_id, $polo->pol_id);
        foreach ($result as $item) {
            $this->assertContains('Reprovado', $item->status);
        }

        $data = $this->mockMatriculaGeral(['tcc'], 'trancado', 'cursando', 4);
        list($turma, $polo) = $data;

        //Situacao Reprovado para 4 alunos
        $result = $this->repo->getAlunosAptosOrNot($turma->trm_id, $polo->pol_id);
        foreach ($result as $item) {
            $this->assertContains('Trancado', $item->status);
        }

        $data = $this->mockMatriculaGeral(['obrigatoria'], 'cursando', 'cursando', 4);
        list($turma, $polo) = $data;

        //Situacao Cursando com status cursando em uma disciplina obrigatoria para 4 alunos
        $result = $this->repo->getAlunosAptosOrNot($turma->trm_id, $polo->pol_id);
        $this->assertNotEmpty($result);
        foreach ($result as $item) {
            $this->assertContains('Não possui aprovação em todas as disciplinas obrigatórias', $item->status);
        }

        $data = $this->mockMatriculaGeral(['obrigatoria'], 'cursando', 'cursando', 4);
        list($turma, $polo) = $data;

        //Situacao Cursando com status cursando em uma disciplina obrigatoria para 4 alunos
        $result = $this->repo->getAlunosAptosOrNot($turma->trm_id, $polo->pol_id);
        $this->assertNotEmpty($result);
        foreach ($result as $item) {
            $this->assertContains('Não possui aprovação em todas as disciplinas obrigatórias', $item->status);
        }
    }

    public function testVerifyIfAlunoIsAptoOrNot()
    {
        $data = $this->mockMatriculaGeral(['obrigatoria'], 'cursando', 'cursando');
        list(, , $matriculas) = $data;

        $matricula = $matriculas->first();

        //Situacao Cursando com status cursando em uma disciplina obrigatoria para 1 aluno
        $result = $this->invokeMethod($this->repo, 'verifyIfAlunoIsAptoOrNot', [$matricula->mat_id]);
        $this->assertEquals('Não possui aprovação em todas as disciplinas obrigatórias', $result['message']);

        $data = $this->mockMatriculaGeral(['eletiva'], 'cursando', 'cursando', 1, 10, 10, 10, 10);
        list(, , $matriculas) = $data;

        $matricula = $matriculas->first();

        //Situacao Cursando com status cursando em uma disciplina eletiva para 1 aluno
        $result = $this->invokeMethod($this->repo, 'verifyIfAlunoIsAptoOrNot', [$matricula->mat_id]);
        $this->assertEquals('Aluno não atingiu carga horária/creditos minima em algum modulo da matriz curricular', $result['message']);

        $data = $this->mockMatriculaGeral(['eletiva'], 'cursando', 'aprovado_media', 1, 10, 10, 10, 10, 100);
        list(, , $matriculas) = $data;

        $matricula = $matriculas->first();

        //Situacao Cursando com status aprovado_media em uma disciplina eletiva para 1 aluno
        $result = $this->invokeMethod($this->repo, 'verifyIfAlunoIsAptoOrNot', [$matricula->mat_id]);
        $this->assertEquals('Aluno não atingiu carga horária minima do curso', $result['message']);

        $data = $this->mockMatriculaGeral(['eletiva', 'tcc'], 'cursando', 'aprovado_media', 1,
            10, 10, 10, 10, 10, true, true, 2);
        list(, , $matriculas) = $data;

        $matricula = $matriculas->first();

        //Situacao Cursando com status aprovado_media em uma disciplina eletiva para 1 aluno
        $result = $this->invokeMethod($this->repo, 'verifyIfAlunoIsAptoOrNot', [$matricula->mat_id]);
        $this->assertEquals('Apto', $result['message']);

        $data = $this->mockMatriculaGeral(['obrigatoria', 'eletiva', 'tcc'], 'cursando', 'aprovado_media', 1, 10,
            10, 10, 10, 10, false, true, 3);
        list(, , $matriculas, , , , , ) = $data;

        $matricula = $matriculas->first();

        //Situacao com aluno sem TCC lancado
        $result = $this->invokeMethod($this->repo, 'verifyIfAlunoIsAptoOrNot', [$matricula->mat_id]);
        $this->assertEquals('Aluno não possui TCC lançado', $result['message']);

        $data = $this->mockMatriculaGeral(['obrigatoria', 'eletiva', 'tcc'], 'cursando', 'aprovado_media', 1, 10,
            10, 10, 10, 10, true, true, 3, 2, 4);
        list(, , $matriculas, , , , , ) = $data;

        $matricula = $matriculas->first();

        //Situacao com aluno sem titulacao de graducao com curso nivel 4
        $result = $this->invokeMethod($this->repo, 'verifyIfAlunoIsAptoOrNot', [$matricula->mat_id]);
        $this->assertEquals('Aluno não possui titulação de graduação cadastrada', $result['message']);

        $data = $this->mockMatriculaGeral(['obrigatoria', 'eletiva'], 'cursando', 'aprovado_media', 1, 10,
            10, 10, 10, 10, false, true, 2);
        list($turma, $polo, $matriculas, $modulosDisciplina, $moduloMatriz, $curso) = $data;

        $matricula = $matriculas->first();

        $disciplina = factory(Modulos\Academico\Models\Disciplina::class)->create([
            'dis_nvc_id' => $curso->crs_nvc_id,
        ]);

        $moduloDisciplina = factory(Modulos\Academico\Models\ModuloDisciplina::class)->create([
            'mdc_dis_id' => $disciplina->dis_id,
            'mdc_mdo_id' => $moduloMatriz->mdo_id,
            'mdc_tipo_disciplina' => 'tcc'
        ]);

        $professor = factory(Modulos\Academico\Models\Professor::class)->create();

        $titulacaoProfessor = factory(Modulos\Geral\Models\TitulacaoInformacao::class)->create([
            'tin_pes_id' => $professor->pessoa->pes_id,
            'tin_tit_id' => random_int(2, 7),
        ]);

        $ofertaDisciplina = factory(Modulos\Academico\Models\OfertaDisciplina::class)->create([
            'ofd_mdc_id' => $moduloDisciplina->mdc_id,
            'ofd_trm_id' => $turma->trm_id,
            'ofd_per_id' => $turma->trm_per_id,
            'ofd_prf_id' => $professor->prf_id,
            'ofd_tipo_avaliacao' => 'numerica',
            'ofd_qtd_vagas' => 500
        ]);

        $matriculaOfertaDisciplina = factory(Modulos\Academico\Models\MatriculaOfertaDisciplina::class)->create([
            'mof_mat_id' => $matricula->mat_id,
            'mof_ofd_id' => $ofertaDisciplina->ofd_id,
            'mof_tipo_matricula' => 'matriculacomum',
            'mof_situacao_matricula' => 'reprovado_media'
        ]);

        $lacamentosTCC = factory(\Modulos\Academico\Models\LancamentoTcc::class)->create([
            'ltc_mof_id' => $matriculaOfertaDisciplina->mof_id,
            'ltc_prf_id' => $professor->prf_id,
        ]);

        //Situacao com aluno reprovado em uma disciplina TCC
        $result = $this->invokeMethod($this->repo, 'verifyIfAlunoIsAptoOrNot', [$matricula->mat_id]);
        $this->assertEquals('Aluno não possui aprovação na disciplina de TCC', $result['message']);
    }

    public function testVerifyIfAlunoIsAprovadoDisciplinasObrigatorias()
    {
        $data = $this->mockMatriculaGeral(['obrigatoria'], 'cursando', 'cursando');
        list(, , $matriculas) = $data;

        $matricula = $matriculas->first();

        //Situacao Cursando com status cursando em uma disciplina obrigatoria para 1 aluno
        $result = $this->invokeMethod($this->repo, 'verifyIfAlunoIsAprovadoDisciplinasObrigatorias', [$matricula]);
        $this->assertFalse($result);

        $data = $this->mockMatriculaGeral(['obrigatoria'], 'cursando', 'aprovado_media');
        list(, , $matriculas) = $data;

        $matricula = $matriculas->first();

        //Situacao Cursando com status cursando em uma disciplina obrigatoria para 1 aluno
        $result = $this->invokeMethod($this->repo, 'verifyIfAlunoIsAprovadoDisciplinasObrigatorias', [$matricula]);
        $this->assertTrue($result);
    }

    public function testverifyIfAlunoIsAprovadoEletivasModulosMatriz()
    {
        $data = $this->mockMatriculaGeral(['obrigatoria'], 'cursando', 'aprovado_media', 1, 3, 10, 10, 10);
        list(, , $matriculas) = $data;

        $matricula = $matriculas->first();

        //Situacao Cursando com status cursando em uma disciplina obrigatoria
        $result = $this->invokeMethod($this->repo, 'verifyIfAlunoIsAprovadoEletivasModulosMatriz', [$matricula]);
        $this->assertTrue($result);

        $data = $this->mockMatriculaGeral(['eletiva'], 'cursando', 'aprovado_media', 1, 3, 10, 10, 10);
        list(, , $matriculas) = $data;

        $matricula = $matriculas->first();

        //Situacao Cursando com status cursando em uma disciplina eletiva para 1 aluno onde o mesmo não atinge a quantidade de carga horaria minima de eletiva
        $result = $this->invokeMethod($this->repo, 'verifyIfAlunoIsAprovadoEletivasModulosMatriz', [$matricula]);
        $this->assertFalse($result);

        $data = $this->mockMatriculaGeral(['eletiva'], 'cursando', 'aprovado_media', 1, 10, 3, 10, 10);
        list(, , $matriculas) = $data;

        $matricula = $matriculas->first();

        //Situacao Cursando com status aprovado_media em uma disciplina eletiva para 1 aluno onde o mesmo não atinge a quantidade de creditos minimo eletiva
        $result = $this->invokeMethod($this->repo, 'verifyIfAlunoIsAprovadoEletivasModulosMatriz', [$matricula]);
        $this->assertFalse($result);

        $data = $this->mockMatriculaGeral(['eletiva'], 'cursando', 'aprovado_media', 1, 10, 10, 10, 10);
        list(, , $matriculas) = $data;

        $matricula = $matriculas->first();

        //Situacao Cursando com status aprovado_media em uma disciplina eletiva para 1 aluno onde o mesmo atinge todos os requisitos
        $result = $this->invokeMethod($this->repo, 'verifyIfAlunoIsAprovadoEletivasModulosMatriz', [$matricula]);
        $this->assertTrue($result);
    }

    public function testVerifyIfAlunoHaveCargaHorariaMinCurso()
    {
        $data = $this->mockMatriculaGeral(['obrigatoria'], 'cursando', 'aprovado_media', 1, 3, 10, 10, 10);
        list(, , $matriculas) = $data;

        $matricula = $matriculas->first();

        //Situacao Cursando com status aprovado_media em uma disciplina obrigatoria onde o mesmo nao atinge a carga horaria minima do curso
        $result = $this->invokeMethod($this->repo, 'verifyIfAlunoHaveCargaHorariaMinCurso', [$matricula]);
        $this->assertFalse($result);

        $data = $this->mockMatriculaGeral(['obrigatoria'], 'cursando', 'aprovado_media', 1, 10, 10, 10, 10);
        list(, , $matriculas) = $data;

        $matricula = $matriculas->first();

        //Situacao Cursando com status aprovado_media em uma disciplina obrigatoria onde o mesmo atinge a carga horaria minima do curso
        $result = $this->invokeMethod($this->repo, 'verifyIfAlunoHaveCargaHorariaMinCurso', [$matricula]);
        $this->assertTrue($result);
    }

    public function testVerifyIfAlunoAprovadoTcc()
    {
        $data = $this->mockMatriculaGeral(['tcc'], 'cursando', 'aprovado_media', 1,
            10, 10, 10, 10, 10, true, true);
        list(, , $matriculas) = $data;

        $matricula = $matriculas->first();

        //Situacao Cursando com status aprovado_media em uma disciplina TCC para 1 aluno
        $result = $this->invokeMethod($this->repo, 'verifyIfAlunoAprovadoTcc', [$matricula]);
        $this->assertTrue($result);

        $data = $this->mockMatriculaGeral(['tcc'], 'cursando', 'reprovado_media', 1,
            10, 10, 10, 10, 10, true, true);
        list(, , $matriculas) = $data;

        $matricula = $matriculas->first();

        //Situacao Cursando com status reprovado_media em uma disciplina TCC para 1 aluno
        $result = $this->invokeMethod($this->repo, 'verifyIfAlunoAprovadoTcc', [$matricula]);
        $this->assertFalse($result);

        $data = $this->mockMatriculaGeral(['eletiva'], 'cursando', 'reprovado_media', 1,
            10, 10, 10, 10, 10, false, true);
        list(, , $matriculas) = $data;

        $matricula = $matriculas->first();

        //Situacao Cursando com status reprovado_media em uma disciplina eletiva para 1 aluno
        $result = $this->invokeMethod($this->repo, 'verifyIfAlunoAprovadoTcc', [$matricula]);
        $this->assertTrue($result);
    }

    public function testVerifyIfAlunoHaveTccLancado()
    {
        $data = $this->mockMatriculaGeral(['tcc'], 'cursando', 'reprovado_media', 1,
            10, 10, 10, 10, 10, true, true);
        list(, , $matriculas) = $data;

        $matricula = $matriculas->first();

        //Situacao Cursando com status reprovado_media em uma disciplina tcc para 1 aluno
        $result = $this->invokeMethod($this->repo, 'verifyIfAlunoHaveTccLancado', [$matricula]);
        $this->assertFalse($result);

        $data = $this->mockMatriculaGeral(['tcc'], 'cursando', 'aprovado_media', 1,
            10, 10, 10, 10, 10, true, true);
        list(, , $matriculas) = $data;

        $matricula = $matriculas->first();

        //Situacao Cursando com status reprovado_media em uma disciplina tcc para 1 aluno
        $result = $this->invokeMethod($this->repo, 'verifyIfAlunoHaveTccLancado', [$matricula]);
        $this->assertTrue($result);
    }

    public function testVerifyIfAlunoHaveTitulacaoGraduacao()
    {
        $data = $this->mockMatriculaGeral(['eletiva'], 'cursando', 'aprovado_media', 1,
            10, 10, 10, 10, 10, false, true, 1, 1);
        list(, , $matriculas) = $data;

        $matricula = $matriculas->first();

        //Situacao aluno com titulacao de Ensino Médio
        $result = $this->invokeMethod($this->repo, 'verifyIfAlunoHaveTitulacaoGraduacao', [$matricula]);
        $this->assertTrue($result);

        $data = $this->mockMatriculaGeral(['eletiva'], 'cursando', 'aprovado_media', 1,
            10, 10, 10, 10, 10, false, true, 1, 2);
        list(, , $matriculas) = $data;

        $matricula = $matriculas->first();

        //Situacao aluno com titulacao de Graduação
        $result = $this->invokeMethod($this->repo, 'verifyIfAlunoHaveTitulacaoGraduacao', [$matricula]);
        $this->assertFalse($result);
    }

    public function testGetAlunosAptosCertificacao()
    {
        $data = $this->mockMatriculaAptaCertificacao(2);
        list($turma, $polo, $matriculas, $modulosDis, $modulosMatriz) = $data;

        //Situacao com aluno apto para ser certificado
        $result = $this->repo->getAlunosAptosCertificacao($turma->trm_id, $modulosMatriz[0]->mdo_id, $polo->pol_id);
        $this->assertNotEmpty($result['aptos']);
        $this->assertEquals(2, $result['aptosq']);
        $this->assertEmpty($result['certificados']);
        $this->assertEquals(0, $result['certificadosq']);

        $data = $this->mockMatriculaGeral(['obrigatoria', 'eletiva', 'tcc'], 'cursando', 'aprovado_media', 2,
            10, 10, 10, 10, 10, true, true, 3, 1);
        list($turma, $polo, $matriculas, $modulosDis, $moduloMatriz) = $data;

        //Situacao com aluno aprovado em todas as disciplinas
        $result = $this->repo->getAlunosAptosCertificacao($turma->trm_id, $moduloMatriz->mdo_id, $polo->pol_id);
        $this->assertEmpty($result['aptos']);
        $this->assertEquals(0, $result['aptosq']);
        $this->assertEmpty($result['certificados']);
        $this->assertEquals(0, $result['certificadosq']);

        $data = $this->mockMatriculaGeral(['eletiva'], 'concluido', 'aprovado_media', 1,
            10, 10, 10, 10, 10, false, true, 1, 1);
        list($turma, $polo, $matriculas, $modulosDis, $moduloMatriz) = $data;

        $matricula = $matriculas->first();

        $this->actingAs(factory(Usuario::class)->create());

        $data = factory(Registro::class)->raw();
        $data = array_merge([
            'matricula' => $matricula->mat_id,
            'tipo_livro' => 'CERTIFICADO',
            'modulo' => $moduloMatriz->mdo_id
        ], $data);

        $registros[] = $this->regrepo->create($data);

        //Situacao com alunos certificados
        $result = $this->repo->getAlunosAptosCertificacao($turma->trm_id, $moduloMatriz->mdo_id, $polo->pol_id);
        $this->assertEmpty($result['aptos']);
        $this->assertEquals(0, $result['aptosq']);
        $this->assertNotEmpty($result['certificados']);
        $this->assertEquals(1, $result['certificadosq']);


        $curso = factory(Modulos\Academico\Models\Curso::class)->create([
            'crs_nome' => 'Curso 1',
            'crs_nvc_id' => 1,
        ]);

        $matrizCurricular = factory(Modulos\Academico\Models\MatrizCurricular::class)->create([
            'mtc_crs_id' => $curso->crs_id
        ]);

        $oferta = factory(Modulos\Academico\Models\OfertaCurso::class)->create([
            'ofc_crs_id' => $curso->crs_id,
            'ofc_mtc_id' => $matrizCurricular->mtc_id,
        ]);

        $turma = factory(Modulos\Academico\Models\Turma::class)->create([
            'trm_ofc_id' => $oferta->ofc_id,
        ]);

        $polo = factory(Modulos\Academico\Models\Polo::class)->create();
        $oferta->polos()->attach($polo->pol_id);

        $moduloMatriz = factory(Modulos\Academico\Models\ModuloMatriz::class)->create([
            'mdo_mtc_id' => $matrizCurricular->mtc_id,
        ]);

        //Situacao com turma sem matriculas e sem passar o polo como parametro
        $result = $this->repo->getAlunosAptosCertificacao($turma->trm_id, $moduloMatriz->mdo_id, null);
        $this->assertEmpty($result['aptos']);
        $this->assertEmpty($result['certificados']);
    }

    public function testVerifyIfAlunoIsAptoCertificacao()
    {
        $data = $this->mockMatriculaGeral(['eletiva'], 'concluido', 'aprovado_media', 1,
            10, 10, 10, 10, 10, false, true, 1, 1);
        list($turma, , $matriculas, , $moduloMatriz) = $data;

        $matricula = $matriculas->first();

        //Situacao aluno com situacao de matricula concluido
        $result = $this->invokeMethod($this->repo, 'verifyIfAlunoIsAptoCertificacao', [$matricula->mat_id, $turma->trm_id, $moduloMatriz->mdo_id]);
        $this->assertFalse($result);

        $data = $this->mockMatriculaGeral(['eletiva'], 'cursando', 'reprovado_media', 1,
            10, 10, 10, 10, 10, false, false, 1, 1);
        list($turma, , $matriculas, , $moduloMatriz) = $data;

        $matricula = $matriculas->first();

        //Quando o modulo que for passado é menor que a quantidade modulos existentes
        $result = $this->invokeMethod($this->repo, 'verifyIfAlunoIsAptoCertificacao', [$matricula->mat_id, $turma->trm_id, 1]);
        $this->assertFalse($result);

        $data = $this->mockMatriculaGeral(['eletiva'], 'cursando', 'reprovado_media', 1,
            10, 10, 10, 10, 10, false, false, 1, 1);
        list($turma, , $matriculas, , $moduloMatriz) = $data;

        $matricula = $matriculas->first();

        //Situacao onde o aluno nao possui matricula em nenhuma oferta
        $result = $this->invokeMethod($this->repo, 'verifyIfAlunoIsAptoCertificacao', [$matricula->mat_id, $turma->trm_id, $moduloMatriz->mdo_id]);
        $this->assertFalse($result);

        $data = $this->mockMatriculaGeral(['eletiva'], 'cursando', 'reprovado_media', 1,
            10, 10, 10, 10, 10, false, true, 1, 1);
        list($turma, , $matriculas, , $moduloMatriz) = $data;

        $matricula = $matriculas->first();

        //Situacao onde o aluno não atinge a carga horaria minima de disciplinas eletivas do módulo
        $result = $this->invokeMethod($this->repo, 'verifyIfAlunoIsAptoCertificacao', [$matricula->mat_id, $turma->trm_id, $moduloMatriz->mdo_id]);
        $this->assertFalse($result);

        $data = $this->mockMatriculaGeral(['eletiva'], 'cursando', 'aprovado_media', 1,
            10, 10, 10, 3, 10, false, true, 1, 1);
        list($turma, , $matriculas, , $moduloMatriz) = $data;

        $matricula = $matriculas->first();

        //Situacao onde o aluno não atinge os creditos minimos de disciplinas eletivas do módulo
        $result = $this->invokeMethod($this->repo, 'verifyIfAlunoIsAptoCertificacao', [$matricula->mat_id, $turma->trm_id, $moduloMatriz->mdo_id]);
        $this->assertFalse($result);

        $data = $this->mockMatriculaGeral(['obrigatoria', 'eletiva'], 'cursando', 'aprovado_media', 1,
            10, 10, 10, 10, 10, false, true, 2, 1);
        list($turma, , $matriculas, , $moduloMatriz) = $data;

        $matricula = $matriculas->first();

        //Situacao onde o aluno esta apto a ser certificado
        $result = $this->invokeMethod($this->repo, 'verifyIfAlunoIsAptoCertificacao', [$matricula->mat_id, $turma->trm_id, $moduloMatriz->mdo_id]);
        $this->assertTrue($result);
    }

    public function testConcluirMatricula()
    {
        $data = $this->mockMatriculaGeral(['obrigatoria'], 'cursando', 'cursando');
        list(, , $matriculas) = $data;

        $matricula = $matriculas->first();

        //Situacao onde o aluno nao podera ter a sua matricula concluida
        $result = $this->repo->concluirMatricula($matricula->mat_id);
        $this->assertFalse($result);

        $data = $this->mockMatriculaGeral(['eletiva', 'tcc'], 'cursando', 'aprovado_media', 1,
            10, 10, 10, 10, 10, true, true, 2);
        list(, , $matriculas) = $data;

        $matricula = $matriculas->first();

        //Situacao onde o aluno esta apto para ter a matricula concluida
        $result = $this->repo->concluirMatricula($matricula->mat_id);
        $this->assertEquals($matricula->mat_id, $result->mat_id);
        $this->assertEquals('concluido', $result->mat_situacao);
    }

    public function testPaginateRequestByOfertaCurso()
    {
        $data = $this->mockMatriculaGeral(['obrigatoria'], 'cursando', 'cursando');
        list($turma) = $data;

        $requestParameters = [
            'trm_id' => $turma->trm_id,
            'mat_situacao' => 'cursando',
            'field' => 'trm_id',
            'sort' => 'asc'
        ];

        //Passando o campo trm_id
        $response = $this->repo->paginateRequestByOfertaCurso($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());

        $data = $this->mockMatriculaGeral(['obrigatoria'], 'cursando', 'cursando');
        list($turma, $polo) = $data;

        $requestParameters = [
            'trm_id' => $turma->trm_id,
            'pol_id' => $polo->pol_id,
            'mat_situacao' => 'cursando',
            'field' => 'trm_id',
            'sort' => 'asc'
        ];

        //Passando o campo trm_id e pol_id
        $response = $this->repo->paginateRequestByOfertaCurso($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());

        $requestParameters = [

        ];

        //Passando parametros vazio
        $response = $this->repo->paginateRequestByOfertaCurso($requestParameters);
        $this->assertEmpty($response);

        $requestParameters = [
            'mat_situacao' => 'cursando',
            'field' => 'trm_id',
            'sort' => 'asc'
        ];

        //Passando parametros sem trm_id
        $response = $this->repo->paginateRequestByOfertaCurso($requestParameters);
        $this->assertEmpty($response);
    }

    public function testFindAllBySitucao()
    {
        $data = $this->mockMatriculaGeral(['obrigatoria'], 'cursando', 'cursando');
        list($turma, $polo, $matriculas) = $data;

        $matricula = $matriculas->first();

        $requestParameters = [
            'trm_id' => $turma->trm_id,
            'pol_id' => $polo->pol_id,
            'mat_situacao' => 'cursando'
        ];

        $this->docrepo->create(['doc_pes_id' => $matricula->aluno->pessoa->pes_id, 'doc_tpd_id' => 2, 'doc_conteudo' => '123456', 'doc_data_expedicao' => '10/10/2000']);
        $this->docrepo->create(['doc_pes_id' => $matricula->aluno->pessoa->pes_id, 'doc_tpd_id' => 1, 'doc_conteudo' => '123456']);

        //Criando doc de pessoa
        $response = $this->repo->findAllBySitucao($requestParameters);
        $this->assertNotEmpty($response);

        $data = $this->mockMatriculaGeral(['obrigatoria'], 'cursando', 'cursando');
        list($turma, $polo) = $data;


        $requestParameters = [
            'trm_id' => $turma->trm_id,
            'pol_id' => $polo->pol_id,
            'mat_situacao' => 'cursando'
        ];

        //Sem doc de pessoa
        $response = $this->repo->findAllBySitucao($requestParameters);
        $this->assertNotEmpty($response);


        $curso = factory(Modulos\Academico\Models\Curso::class)->create([
            'crs_nome' => 'Curso 1',
            'crs_nvc_id' => 1,
        ]);

        $matrizCurricular = factory(Modulos\Academico\Models\MatrizCurricular::class)->create([
            'mtc_crs_id' => $curso->crs_id,
        ]);

        $oferta = factory(Modulos\Academico\Models\OfertaCurso::class)->create([
            'ofc_crs_id' => $curso->crs_id,
            'ofc_mtc_id' => $matrizCurricular->mtc_id,
        ]);

        $turma = factory(Modulos\Academico\Models\Turma::class)->create([
            'trm_ofc_id' => $oferta->ofc_id,
        ]);

        $polo = factory(Modulos\Academico\Models\Polo::class)->create();
        $oferta->polos()->attach($polo->pol_id);

        $requestParameters = [
            'trm_id' => $turma->trm_id,
            'pol_id' => $polo->pol_id,
            'mat_situacao' => 'cursando'
        ];

        //Passando turma sem matricula
        $response = $this->repo->findAllBySitucao($requestParameters);
        $this->assertEmpty($response);
    }

    public function testGetPrintData()
    {
        $data = $this->mockMatriculaGeral(['eletiva'], 'concluido', 'aprovado_media', 1,
            10, 10, 10, 10, 10, false, true, 1, 1);
        list(, , $matriculas, , $moduloMatriz) = $data;

        $matricula = $matriculas->first();

        //Situacao com aluno sem certificado
        $result = $this->repo->getPrintData($matricula->mat_id, $moduloMatriz->mdo_id);
        $this->assertNull($result);

        $data = $this->mockMatriculaGeral(['eletiva'], 'concluido', 'aprovado_media', 1,
            10, 10, 10, 10, 10, false, true, 1, 1);
        list(, , $matriculas, , $moduloMatriz) = $data;

        $matricula = $matriculas->first();

        $this->docrepo->create(['doc_pes_id' => $matricula->aluno->pessoa->pes_id, 'doc_tpd_id' => 2, 'doc_conteudo' => '123456', 'doc_data_expedicao' => '10/10/2000']);
        $this->docrepo->create(['doc_pes_id' => $matricula->aluno->pessoa->pes_id, 'doc_tpd_id' => 1, 'doc_conteudo' => '123456']);

        $this->actingAs(factory(Usuario::class)->create());

        $data = factory(Registro::class)->raw();
        $data = array_merge([
            'matricula' => $matricula->mat_id,
            'tipo_livro' => 'CERTIFICADO',
            'modulo' => $moduloMatriz->mdo_id
        ], $data);

        $this->regrepo->create($data);

        //Situacao com aluno certificado e com documentos
        $result = $this->repo->getPrintData($matricula->mat_id, $moduloMatriz->mdo_id);
        $this->assertNotEmpty($result['DESCRICAOMODULO']);
        $this->assertNotEmpty($result['QUALIFICACAOMODULO']);
        $this->assertNotEmpty($result['CARGAHORARIAMODULO']);
        $this->assertNotEmpty($result['DISCIPLINAS']);
        $this->assertNotEmpty($result['EIXOCURSO']);
        $this->assertNotEmpty($result['LIVRO']);
        $this->assertNotEmpty($result['FOLHA']);
        $this->assertNotEmpty($result['REGISTRO']);
        $this->assertNotEmpty($result['COEFICIENTEDOMODULO']);
        $this->assertNotEmpty($result['PESSOANOME']);
        $this->assertNotEmpty($result['PESSOACPF']);
    }

    public function testGetMatriculasPorStatus()
    {
        $this->mockMatriculaGeral(['eletiva'], 'concluido');

        $result = $this->repo->getMatriculasPorStatus();
        $result = array_pop($result);
        $this->assertEquals(1, $result->quantidade);
    }

    public function testGetMatriculasPorMesUltimosSeisMeses()
    {
        $fimPeriodo = new \DateTime('first day of next month');
        $inicioPeriodo = $fimPeriodo->sub(new \DateInterval('P6M'));

        // Cria 10 registros de matricula a cada mes nos ultimos 6 meses
        for ($i = 0; $i < 6; $i++) {
            for ($j = 0; $j < 10; $j++) {
                $matricula = factory(Matricula::class)->raw();

                $matricula['mat_data_conclusao'] = $fimPeriodo->format('d/m/Y');

                $matricula = array_merge($matricula, [
                    'created_at' => $inicioPeriodo->format('Y-m-d H:i:s'),
                    'updated_at' => $inicioPeriodo->format('Y-m-d H:i:s'),
                ]);

                DB::table('acd_matriculas')->insert($matricula);
            }

            $inicioPeriodo = $inicioPeriodo->add(new \DateInterval('P1M'));
        }

        $result = $this->repo->getMatriculasPorMesUltimosSeisMeses();

        foreach ($result as $item) {
            $this->assertTrue(array_key_exists('mes', $item));
            $this->assertEquals(10, $item['quantidade']);
        }
    }

    private function mockMatriculaGeral(array $tipoDisciplina = ['eletiva'],
                                        $situacaoMatricula = 'cursando',
                                        $situacaoMatriculaDisciplina = 'cursando',
                                        $qtdAlunos = 1,
                                        $cargaHorariaDis = 10,
                                        $creditosDis = 10,
                                        $cargaHorariaMinEle = null,
                                        $creditosMinEle = null,
                                        $qtdHorasMatriz = 10,
                                        $tcc = false,
                                        $ofertaDis = true,
                                        $qtdDis = 1,
                                        $titulacaoAluno = 2,
                                        $nivelCurso = 1)
    {
        $curso = factory(Modulos\Academico\Models\Curso::class)->create([
            'crs_nome' => 'Curso 1',
            'crs_nvc_id' => $nivelCurso,
        ]);

        $matrizCurricular = factory(Modulos\Academico\Models\MatrizCurricular::class)->create([
            'mtc_crs_id' => $curso->crs_id,
            'mtc_horas' => $qtdHorasMatriz
        ]);

        $oferta = factory(Modulos\Academico\Models\OfertaCurso::class)->create([
            'ofc_crs_id' => $curso->crs_id,
            'ofc_mtc_id' => $matrizCurricular->mtc_id,
        ]);

        $turma = factory(Modulos\Academico\Models\Turma::class)->create([
            'trm_ofc_id' => $oferta->ofc_id,
        ]);

        $polo = factory(Modulos\Academico\Models\Polo::class)->create();
        $oferta->polos()->attach($polo->pol_id);

        $grupo = factory(Modulos\Academico\Models\Grupo::class)->create([
            'grp_trm_id' => $turma->trm_id,
            'grp_pol_id' => $polo->pol_id
        ]);

        $moduloMatriz = factory(Modulos\Academico\Models\ModuloMatriz::class)->create([
            'mdo_mtc_id' => $matrizCurricular->mtc_id,
            'mdo_cargahoraria_min_eletivas' => ($cargaHorariaMinEle) ? $cargaHorariaMinEle : null,
            'mdo_creditos_min_eletivas' => ($creditosMinEle) ? $creditosMinEle : null,
        ]);

        $disciplina = factory(Modulos\Academico\Models\Disciplina::class, $qtdDis)->create([
            'dis_nvc_id' => $curso->crs_nvc_id,
            'dis_carga_horaria' => $cargaHorariaDis,
            'dis_creditos' => $creditosDis
        ]);

        $modulosDisciplina = new \Illuminate\Support\Collection();
        foreach ($disciplina as $key => $dis) {
            $modulosDisciplina[] = factory(Modulos\Academico\Models\ModuloDisciplina::class)->create([
                'mdc_dis_id' => $dis->dis_id,
                'mdc_mdo_id' => $moduloMatriz->mdo_id,
                'mdc_tipo_disciplina' => $tipoDisciplina[$key]
            ]);
        }

        $professor = factory(Modulos\Academico\Models\Professor::class)->create();

        factory(Modulos\Geral\Models\TitulacaoInformacao::class)->create([
            'tin_pes_id' => $professor->pessoa->pes_id,
            'tin_tit_id' => random_int(2, 7),
        ]);

        $ofertasDisciplina = new \Illuminate\Support\Collection();
        if ($ofertaDis) {
            foreach ($modulosDisciplina as $moduloDisciplina) {
                $ofertasDisciplina[] = factory(Modulos\Academico\Models\OfertaDisciplina::class)->create([
                    'ofd_mdc_id' => $moduloDisciplina->mdc_id,
                    'ofd_trm_id' => $turma->trm_id,
                    'ofd_per_id' => $turma->trm_per_id,
                    'ofd_prf_id' => $professor->prf_id,
                    'ofd_tipo_avaliacao' => 'numerica',
                    'ofd_qtd_vagas' => 500
                ]);
            }
        }

        $alunos = factory(Modulos\Academico\Models\Aluno::class, $qtdAlunos)->create();

        factory(\Modulos\Geral\Models\Titulacao::class, 7)->create();

        $titulacoes = new \Illuminate\Support\Collection();
        foreach ($alunos as $aluno) {
            $titulacoes[] = factory(Modulos\Geral\Models\TitulacaoInformacao::class)->create([
                'tin_pes_id' => $aluno->pessoa->pes_id,
                'tin_tit_id' => $titulacaoAluno,
            ]);
        }

        $matriculas = new \Illuminate\Support\Collection();
        foreach ($alunos as $aluno) {
            $matriculas[] = factory(Modulos\Academico\Models\Matricula::class)->create([
                'mat_alu_id' => $aluno->alu_id,
                'mat_trm_id' => $turma->trm_id,
                'mat_pol_id' => $polo->pol_id,
                'mat_grp_id' => $grupo->grp_id,
                'mat_situacao' => $situacaoMatricula,
                'mat_modo_entrada' => 'vestibular',
                'mat_data_conclusao' => '15/11/2015',
            ]);
        }

        if ($ofertaDis) {
            $matriculasOfertaDisciplina = new \Illuminate\Support\Collection();
            foreach ($matriculas as $matricula) {
                foreach ($ofertasDisciplina as $ofertaDisciplina) {
                    $matriculasOfertaDisciplina[] = factory(Modulos\Academico\Models\MatriculaOfertaDisciplina::class)->create([
                        'mof_mat_id' => $matricula->mat_id,
                        'mof_ofd_id' => $ofertaDisciplina->ofd_id,
                        'mof_tipo_matricula' => 'matriculacomum',
                        'mof_situacao_matricula' => $situacaoMatriculaDisciplina
                    ]);
                }
            }
        }

        if ($tcc) {
            $lacamentosTCC = new \Illuminate\Support\Collection();
            foreach ($matriculasOfertaDisciplina as $matriculaOfertaDisciplina) {
                $lacamentosTCC[] = factory(\Modulos\Academico\Models\LancamentoTcc::class)->create([
                    'ltc_mof_id' => $matriculaOfertaDisciplina->mof_id,
                    'ltc_prf_id' => $professor->prf_id,
                ]);
            }
        }

        return [$turma, $polo, $matriculas, $modulosDisciplina, $moduloMatriz, $curso];
    }

    private function mockMatriculaAptaCertificacao($qtdAlunos)
    {
        $matrizCurricular = factory(Modulos\Academico\Models\MatrizCurricular::class)->create([
            'mtc_crs_id' => factory(Modulos\Academico\Models\Curso::class)->create([
                'crs_nome' => 'Curso 1',
                'crs_nvc_id' => 1, // nivel curso tecnico
            ])->crs_id,
            'mtc_horas' => 40
        ]);

        $turma = factory(Modulos\Academico\Models\Turma::class)->create([
            'trm_ofc_id' => factory(Modulos\Academico\Models\OfertaCurso::class)->create([
                'ofc_crs_id' => $matrizCurricular->mtc_crs_id,
                'ofc_mtc_id' => $matrizCurricular->mtc_id,
            ])->ofc_id,
        ]);

        $polo = factory(Modulos\Academico\Models\Polo::class)->create();
        $turma->ofertacurso->polos()->attach($polo->pol_id);

        $modulosMatriz = factory(Modulos\Academico\Models\ModuloMatriz::class, 2)->create([
            'mdo_mtc_id' => $matrizCurricular->mtc_id,
            'mdo_cargahoraria_min_eletivas' => 10,
            'mdo_creditos_min_eletivas' => 5,
        ]);

        $modulosDisciplina = new \Illuminate\Support\Collection();
        foreach ($modulosMatriz as $modulo) {
            foreach (['obrigatoria', 'eletiva'] as $tipo) {
                $modulosDisciplina[] = factory(Modulos\Academico\Models\ModuloDisciplina::class)->create([
                    'mdc_dis_id' => factory(Modulos\Academico\Models\Disciplina::class)->create([
                        'dis_nvc_id' => $matrizCurricular->curso->crs_nvc_id,
                        'dis_carga_horaria' => 10,
                        'dis_creditos' => 5
                    ])->dis_id,
                    'mdc_mdo_id' => $modulo->mdo_id,
                    'mdc_tipo_disciplina' => $tipo
                ]);
            }
        }

        $professor = factory(Modulos\Academico\Models\Professor::class)->create();
        factory(Modulos\Geral\Models\TitulacaoInformacao::class)->create([
            'tin_pes_id' => $professor->pessoa->pes_id,
            'tin_tit_id' => random_int(2, 7),
        ]);

        $ofertasDisciplina = new \Illuminate\Support\Collection();
        foreach ($modulosDisciplina as $moduloDisciplina) {
            $ofertasDisciplina[] = factory(Modulos\Academico\Models\OfertaDisciplina::class)->create([
                'ofd_mdc_id' => $moduloDisciplina->mdc_id,
                'ofd_trm_id' => $turma->trm_id,
                'ofd_per_id' => $turma->trm_per_id,
                'ofd_prf_id' => $professor->prf_id,
                'ofd_tipo_avaliacao' => 'numerica',
                'ofd_qtd_vagas' => 500
            ]);
        }

        $alunos = factory(Modulos\Academico\Models\Aluno::class, $qtdAlunos)->create();
        factory(\Modulos\Geral\Models\Titulacao::class, 7)->create();

        foreach ($alunos as $aluno) {
            factory(Modulos\Geral\Models\TitulacaoInformacao::class)->create([
                'tin_pes_id' => $aluno->pessoa->pes_id,
                'tin_tit_id' => 2,
            ]);
        }

        $matriculas = new \Illuminate\Support\Collection();
        foreach ($alunos as $aluno) {
            $matriculas[] = factory(Modulos\Academico\Models\Matricula::class)->create([
                'mat_alu_id' => $aluno->alu_id,
                'mat_trm_id' => $turma->trm_id,
                'mat_pol_id' => $polo->pol_id,
                'mat_situacao' => 'cursando',
                'mat_modo_entrada' => 'vestibular',
            ]);
        }

        foreach ($matriculas as $matricula) {
            foreach ($ofertasDisciplina as $key => $ofertaDisciplina) {
                $status = 'aprovado_media';
                if ($key > 2) {
                    $status = 'reprovado_media';
                }
                factory(Modulos\Academico\Models\MatriculaOfertaDisciplina::class)->create([
                    'mof_mat_id' => $matricula->mat_id,
                    'mof_ofd_id' => $ofertaDisciplina->ofd_id,
                    'mof_tipo_matricula' => 'matriculacomum',
                    'mof_situacao_matricula' => $status
                ]);
            }
        }
        return [$turma, $polo, $matriculas, $modulosDisciplina, $modulosMatriz, $matrizCurricular->curso];
    }
}
