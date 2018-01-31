<?php

use Carbon\Carbon;
use Modulos\Academico\Models\Registro;
use Modulos\Academico\Repositories\RegistroRepository;
use Tests\ModulosTestCase;
use Tests\Helpers\Reflection;
use Modulos\Academico\Models\Curso;
use Modulos\Academico\Models\Vinculo;
use Modulos\Seguranca\Models\Usuario;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Modulos\Academico\Models\Matricula;
use Illuminate\Database\Eloquent\Collection;
use Stevebauman\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Geral\Repositories\DocumentoRepository;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modulos\Academico\Repositories\MatriculaCursoRepository;

class MatriculaCursoTest extends ModulosTestCase
{
    use DatabaseTransactions,
        WithoutMiddleware,
        Reflection;

    protected $repo;

    protected $regrepo;

    public function setUp()
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

        return [$curso, $oferta, $turma, $polo, $grupo, $aluno];
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
        $data['mat_modo_entrada'] = $entry->getOriginal('mat_modo_entrada');
        $data['mat_situacao'] = 'Evadido';

        $return = $this->repo->update($data, $id);
        $data['mat_data_conclusao'] = $entry->getOriginal('mat_data_conclusao');

        $fromRepository = $this->repo->find($id);
        $fromRepositoryData = $fromRepository->toArray();
        $fromRepositoryData['mat_modo_entrada'] = $fromRepository->getOriginal('mat_modo_entrada');
        $fromRepositoryData['mat_data_conclusao'] = $fromRepository->getOriginal('mat_data_conclusao');

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
            'mat_situacao' => 'search_mat_situacao'
        ]);

        $searchResult = $this->repo->search(array(['mat_situacao', '=', 'search_mat_situacao']));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(\Modulos\Academico\Models\Matricula::class, 2)->create();

        $entry = factory(\Modulos\Academico\Models\Matricula::class)->create([
            'mat_situacao' => "mat_situacao_to_find"
        ]);

        $expected = [
            'mat_id' => $entry->mat_id,
            'mat_situacao' => $entry->mat_situacao
        ];

        $searchResult = $this->repo->search(array(['mat_situacao', '=', "mat_situacao_to_find"]), ['mat_id', 'mat_situacao']);

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
            'mat_situacao' => 'mat_situacao_to_search',
        ]);

        $search = [
            [
                'field' => 'mat_situacao',
                'type' => '=',
                'term' => 'mat_situacao_to_search'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('mat_situacao_to_search', $response->first()->mat_situacao);
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

        $this->expectException(\Exception::class);
        putenv("APP_DEBUG=true");
        $result = $this->repo->createMatricula($aluno->alu_id, $options);
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
            'mof_situacao_matricula' => 'aprovado'
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

//    public function testGetAlunosAptosOrNot()
//    {
//        $curso = factory(Modulos\Academico\Models\Curso::class)->create([
//            'crs_nome' => 'Curso 1',
//            'crs_nvc_id' => 1,
//        ]);
//
//        $matrizCurricular = factory(Modulos\Academico\Models\MatrizCurricular::class)->create([
//            'mtc_crs_id' => $curso->crs_id
//        ]);
//
//        $oferta = factory(Modulos\Academico\Models\OfertaCurso::class)->create([
//            'ofc_crs_id' => $curso->crs_id,
//            'ofc_mtc_id' => $matrizCurricular->mtc_id,
//        ]);
//
//        $turma = factory(Modulos\Academico\Models\Turma::class)->create([
//            'trm_ofc_id' => $oferta->ofc_id,
//        ]);
//
//        $polo = factory(Modulos\Academico\Models\Polo::class)->create();
//        $oferta->polos()->attach($polo->pol_id);
//
//        //Turma e Polo sem matriculas cadastradas
//        $result = $this->repo->getAlunosAptosOrNot($turma->trm_id, $polo->pol_id);
//        $this->assertEmpty($result);
//
//        $data = $this->mockMatriculaGeral(['tcc'], 'concluido', 'cursando', 4);
//        list($turma, $polo) = $data;
//
//        //Situacao Concluido para 4 alunos
//        $result = $this->repo->getAlunosAptosOrNot($turma->trm_id, $polo->pol_id);
//        foreach ($result as $item){
//            $this->assertContains('Concluído', $item->status);
//        }
//
//        $data = $this->mockMatriculaGeral(['tcc'], 'reprovado', 'cursando', 4);
//        list($turma, $polo) = $data;
//
//        //Situacao Reprovado para 4 alunos
//        $result = $this->repo->getAlunosAptosOrNot($turma->trm_id, $polo->pol_id);
//        foreach ($result as $item){
//            $this->assertContains('Reprovado', $item->status);
//        }
//
//        $data = $this->mockMatriculaGeral(['tcc'], 'trancado', 'cursando', 4);
//        list($turma, $polo) = $data;
//
//        //Situacao Reprovado para 4 alunos
//        $result = $this->repo->getAlunosAptosOrNot($turma->trm_id, $polo->pol_id);
//        foreach ($result as $item){
//            $this->assertContains('Trancado', $item->status);
//        }
//
//        $data = $this->mockMatriculaGeral(['obrigatoria'], 'cursando', 'cursando', 4);
//        list($turma, $polo) = $data;
//
//        //Situacao Cursando com status cursando em uma disciplina obrigatoria para 4 alunos
//        $result = $this->repo->getAlunosAptosOrNot($turma->trm_id, $polo->pol_id);
//        $this->assertNotEmpty($result);
//        foreach ($result as $item){
//            $this->assertContains('Não possui aprovação em todas as disciplinas obrigatórias', $item->status);
//        }
//
//        $data = $this->mockMatriculaGeral(['obrigatoria'], 'cursando', 'cursando', 4);
//        list($turma, $polo) = $data;
//
//        //Situacao Cursando com status cursando em uma disciplina obrigatoria para 4 alunos
//        $result = $this->repo->getAlunosAptosOrNot($turma->trm_id, $polo->pol_id);
//        $this->assertNotEmpty($result);
//        foreach ($result as $item){
//            $this->assertContains('Não possui aprovação em todas as disciplinas obrigatórias', $item->status);
//        }
//    }
//
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
        $data = $this->mockMatriculaGeral(['obrigatoria', 'eletiva'], 'cursando', 'aprovado_media', 2,
            10, 10, 10, 10, 10, false, true, 2, 1);
        list($turma, $polo, $matriculas, $modulosDis, $moduloMatriz) = $data;

        $matricula = $matriculas->first();

        //Situacao com aluno apto para ser certificado
        $result = $this->repo->getAlunosAptosCertificacao($turma->trm_id, $moduloMatriz->mdo_id, $polo->pol_id);
        $this->assertNotEmpty($result['aptos']);
        $this->assertEquals($result['aptosq'], 2);

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

        //Situacao aluno com titulacao de Graduação
        $result = $this->repo->getAlunosAptosCertificacao($turma->trm_id, $moduloMatriz->mdo_id, $polo->pol_id);
        $this->assertNotEmpty($result['certificados']);
        $this->assertEquals($result['certificadosq'], 1);
    }

    //TODO criar factory para lancamento de TCC
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
                                        $titulacaoAluno = 2)
    {
        $curso = factory(Modulos\Academico\Models\Curso::class)->create([
            'crs_nome' => 'Curso 1',
            'crs_nvc_id' => 1,
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
            "mdo_cargahoraria_min_eletivas" => ($cargaHorariaMinEle) ? $cargaHorariaMinEle : null,
            "mdo_creditos_min_eletivas" => ($creditosMinEle) ? $creditosMinEle : null,
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

        $titulacaoProfessor = factory(Modulos\Geral\Models\TitulacaoInformacao::class)->create([
            'tin_pes_id' => $professor->pessoa->pes_id,
            'tin_tit_id' => random_int(2, 7),
        ]);

        if ($ofertaDis) {
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
                'mat_data_conclusao' => '15/11/2015'
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


        return [$turma, $polo, $matriculas, $modulosDisciplina, $moduloMatriz];
    }
}
