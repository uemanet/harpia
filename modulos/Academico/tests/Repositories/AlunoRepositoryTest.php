<?php

use Tests\ModulosTestCase;
use Tests\Helpers\Reflection;
use Modulos\Geral\Models\Pessoa;
use Modulos\Academico\Models\Aluno;
use Modulos\Academico\Models\Curso;
use Modulos\Geral\Models\Documento;
use Modulos\Academico\Models\Vinculo;
use Modulos\Seguranca\Models\Usuario;
use Modulos\Academico\Models\Matricula;
use Illuminate\Support\Facades\Artisan;
use Uemanet\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Academico\Repositories\AlunoRepository;

class AlunoRepositoryTest extends ModulosTestCase
{
    use Reflection;

    public function setUp(): void
    {
        parent::setUp();
        $this->repo = $this->app->make(AlunoRepository::class);
        $this->table = 'acd_alunos';
    }

    private function mockVinculo($withBond = true)
    {
        // Usuario, Curso e Vinculo
        $user = factory(Usuario::class)->create();

        $curso = factory(Curso::class)->create();

        if ($withBond) {
            factory(Vinculo::class)->create([
                'ucr_usr_id' => $user->usr_id,
                'ucr_crs_id' => $curso->crs_id
            ]);
        }

        // Mock de matricula
        $pessoa = factory(Pessoa::class)->create([
            'pes_nome' => 'Irineu',
        ]);

        $documento = factory(Documento::class)->create([
            'doc_pes_id' => $pessoa->pes_id,
            'doc_conteudo' => '123456789'
        ]);

        $aluno = factory(Aluno::class)->create([
            'alu_pes_id' => $pessoa->pes_id
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

        $grupo = factory(Modulos\Academico\Models\Grupo::class)->create([
            'grp_trm_id' => $turma->trm_id,
            'grp_pol_id' => $polo->pol_id
        ]);

        $matricula = factory(\Modulos\Academico\Models\Matricula::class)->create([
            'mat_alu_id' => $aluno->alu_id,
            'mat_trm_id' => $turma->trm_id,
            'mat_pol_id' => $polo->pol_id,
            'mat_grp_id' => $grupo->grp_id,
        ]);

        return [$user, $aluno, $matricula];
    }

    public function testCreate()
    {
        $data = factory(Aluno::class)->raw();
        $entry = $this->repo->create($data);

        $this->assertInstanceOf(Aluno::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->toArray());
    }

    public function testFind()
    {
        $entry = factory(Aluno::class)->create();
        $id = $entry->alu_id;
        $fromRepository = $this->repo->find($id);

        $this->assertInstanceOf(Aluno::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepository->toArray());
        $this->assertEquals($entry->toArray(), $fromRepository->toArray());
    }

    public function testUpdate()
    {
        factory(Pessoa::class, 10);
        $entry = factory(Aluno::class)->create();
        $id = $entry->alu_id;

        $data = $entry->toArray();

        $data['alu_pes_id'] = 5;

        $return = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(Aluno::class, $fromRepository);
        $this->assertEquals($data, $fromRepository->toArray());
    }

    public function testDelete()
    {
        $entry = factory(Aluno::class)->create();
        $id = $entry->alu_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        factory(Aluno::class, 2)->create();

        $expected = Aluno::all()->pluck('alu_pes_id', 'alu_id');
        $fromRepository = $this->repo->lists('alu_pes_id', 'alu_id');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        factory(Aluno::class, 2)->create();

        $pessoa = factory(Pessoa::class)->create([
            'pes_nome' => 'Irineu',
        ]);

        factory(Aluno::class)->create([
            'alu_pes_id' => $pessoa->pes_id
        ]);

        $searchResult = $this->repo->search(array(['pes_nome', '=', 'Irineu']));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(Aluno::class, 2)->create();

        $pessoa = factory(Pessoa::class)->create([
            'pes_nome' => 'Irineu',
        ]);

        $entry = factory(Aluno::class)->create([
            'alu_pes_id' => $pessoa->pes_id
        ]);

        $expected = [
            'alu_id' => $entry->alu_id,
            'pes_nome' => $entry->pessoa->pes_nome
        ];

        $searchResult = $this->repo->search(array(['pes_nome', '=', "Irineu"]), ['alu_id', 'pes_nome']);

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
        $created = factory(Aluno::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(Aluno::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new Aluno();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Aluno::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Aluno::class, 2)->create();

        $sort = [
            'field' => 'alu_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->alu_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Aluno::class, 2)->create();

        $pessoa = factory(Pessoa::class)->create([
            'pes_nome' => 'Irineu',
        ]);

        $documento = factory(Documento::class)->create([
            'doc_pes_id' => $pessoa->pes_id,
            'doc_conteudo' => '123456789'
        ]);

        $entry = factory(Aluno::class)->create([
            'alu_pes_id' => $pessoa->pes_id
        ]);

        $search = [
            [
                'field' => 'pes_nome',
                'type' => '=',
                'term' => 'Irineu'
            ],
            [
                'field' => 'pes_cpf',
                'type' => '=',
                'term' => '123456789'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('Irineu', $response->first()->pes_nome);

        $search = [
            [
                'field' => 'pes_nome',
                'type' => 'like',
                'term' => 'Irineu'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('Irineu', $response->first()->pes_nome);
    }

    public function testPaginateWithSearchWithBonds()
    {
        $this->mockVinculo();
        factory(Aluno::class, 2)->create();

        $pessoa = factory(Pessoa::class)->create([
            'pes_nome' => 'Irineu',
        ]);

        $documento = factory(Documento::class)->create([
            'doc_pes_id' => $pessoa->pes_id,
            'doc_conteudo' => '123456789'
        ]);

        $entry = factory(Aluno::class)->create([
            'alu_pes_id' => $pessoa->pes_id
        ]);

        $search = [
            [
                'field' => 'pes_nome',
                'type' => '=',
                'term' => 'Irineu'
            ],
            [
                'field' => 'pes_cpf',
                'type' => '=',
                'term' => '123456789'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('Irineu', $response->first()->pes_nome);

        $search = [
            [
                'field' => 'pes_nome',
                'type' => 'like',
                'term' => 'Irineu'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('Irineu', $response->first()->pes_nome);
    }

    public function testPaginateRequest()
    {
        factory(Aluno::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'alu_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }

    public function testPaginateRequestWithBonds()
    {
        $requestParameters = [
            'page' => '1',
            'field' => 'alu_id',
            'sort' => 'asc'
        ];

        // SEM VINCULO
        list($user, $aluno) = $this->mockVinculo(false);

        // Login
        $this->actingAs($user);
        factory(Aluno::class, 5)->create();
        $response = $this->repo->paginateRequest($requestParameters, true);

        $pluck = $response->pluck('alu_id', 'pes_nome')->toArray();
        $this->assertFalse(in_array($aluno->alu_id, $pluck));

        // COM VINCULO
        list($user, $aluno) = $this->mockVinculo(true);

        // Login
        $this->actingAs($user);
        $response = $this->repo->paginateRequest($requestParameters, true);

        $pluck = $response->pluck('alu_id', 'pes_nome')->toArray();
        $this->assertTrue(in_array($aluno->alu_id, $pluck));
    }

    public function testPaginateRequestOnlyBonds()
    {
        $requestParameters = [
            'page' => '1',
            'field' => 'alu_id',
            'sort' => 'asc',
            'pes_cpf' => '123456789',
            'pes_nome' => 'Irineu',
        ];

        // SEM VINCULO
        list($user, $aluno) = $this->mockVinculo(false);

        // Login
        $this->actingAs($user);
        factory(Aluno::class, 5)->create();
        $response = $this->repo->paginateRequest($requestParameters, false, true);

        $pluck = $response->pluck('alu_id', 'pes_nome')->toArray();
        $this->assertFalse(in_array($aluno->alu_id, $pluck));

        // COM VINCULO
        list($user, $aluno) = $this->mockVinculo(true);

        // Login
        $this->actingAs($user);
        $response = $this->repo->paginateRequest($requestParameters, false, true);

        $pluck = $response->pluck('alu_id', 'pes_nome')->toArray();
        $this->assertTrue(in_array($aluno->alu_id, $pluck));
    }

    public function testGetCursosNotEmpty()
    {
        $data = factory(Modulos\Academico\Models\Matricula::class)->create();
        $alunoId = $data->aluno->alu_id;
        $cursoId = $data->turma->ofertacurso->curso->crs_id;

        $response = $this->repo->getCursos($alunoId);

        $this->assertEquals($cursoId, $response[0]);
    }

    public function testGetCursosEmpty()
    {
        $data = factory(Modulos\Academico\Models\Aluno::class)->create();
        $alunoId = $data->alu_id;
        $response = $this->repo->getCursos($alunoId);

        $this->assertEmpty($response);
    }

    public function testPaginateAllWithBonds()
    {
        // SEM VINCULO
        list($user, $aluno) = $this->mockVinculo(false);

        // Login
        $this->actingAs($user);
        factory(Aluno::class, 5)->create();
        $response = $this->invokeMethod($this->repo, 'paginateAllWithBonds');

        $this->assertEquals(7, Aluno::all()->count());
        $this->assertNotEmpty($response);
        $pluck = $response->pluck('alu_id', 'pes_nome')->toArray();
        $this->assertFalse(in_array($aluno->alu_id, $pluck));

        // Espera 6 alunos: 6 sem matricula (O Mock gera sempre +2 alunos)
        $this->assertEquals($response->count(), 6);

        // COM VINCULO
        list($user, $aluno) = $this->mockVinculo(true);

        // Login
        $this->actingAs($user);

        $response = $this->invokeMethod($this->repo, 'paginateAllWithBonds');

        $this->assertEquals(9, Aluno::all()->count());
        $this->assertNotEmpty($response);

        $pluck = $response->pluck('alu_id', 'pes_nome')->toArray();

        $this->assertTrue(in_array($aluno->alu_id, $pluck));

        // Espera 8 alunos: 7 sem matricula + 1 Aluno de curso vinculado (O Mock gera sempre +2 alunos)
        $this->assertEquals($response->count(), 8);
    }

    public function testPaginateAllWithBondsWithSearch()
    {
        // SEM VINCULO
        list($user, $aluno) = $this->mockVinculo(false);

        factory(Aluno::class, 5)->create();

        // Login
        $this->actingAs($user);

        $search = [
            [
                'field' => 'pes_nome',
                'type' => 'like',
                'term' => 'Irineu'
            ],
            [
                'field' => 'pes_cpf',
                'type' => '=',
                'term' => '123456789'
            ]
        ];

        $response = $this->invokeMethod($this->repo, 'paginateAllWithBonds', [null, $search]);

        $this->assertEquals(7, Aluno::all()->count());

        // Espera 0 alunos:  O aluno a ser buscado esta matriculado em um curso no qual o $user nao tem vinculo
        $this->assertEquals(0, $response->count());

        // COM VINCULO
        list($user, $aluno) = $this->mockVinculo(true);

        // Login
        $this->actingAs($user);

        $response = $this->invokeMethod($this->repo, 'paginateAllWithBonds', [null, $search]);

        $this->assertEquals(9, Aluno::all()->count());
        $this->assertNotEmpty($response);

        $pluck = $response->pluck('alu_id', 'pes_nome')->toArray();

        $this->assertTrue(in_array($aluno->alu_id, $pluck));
        // Espera 1 aluno:  O aluno a ser buscado esta matriculado em um curso no qual o $user tem vinculo
        $this->assertEquals(1, $response->count());
    }

    public function testPaginateAllWithBondsWithSort()
    {
        // SEM VINCULO
        list($user, $aluno) = $this->mockVinculo(false);

        factory(Aluno::class, 5)->create();

        // Login
        $this->actingAs($user);

        $sort = [
            'field' => 'alu_id',
            'sort' => 'desc'
        ];

        $response = $this->invokeMethod($this->repo, 'paginateAllWithBonds', [$sort]);

        $this->assertEquals(7, Aluno::all()->count());
        $this->assertEquals(2, $response->first()->alu_id);
        $this->assertEquals(7, $response->last()->alu_id);

        // COM VINCULO
        list($user, $aluno) = $this->mockVinculo(true); // Penultimo aluno criado

        // Login
        $this->actingAs($user);

        $response = $this->invokeMethod($this->repo, 'paginateAllWithBonds', [$sort]);

        $this->assertEquals(9, Aluno::all()->count());
        $this->assertNotEmpty($response);

        $pluck = $response->pluck('alu_id', 'pes_nome')->toArray();

        $response->pop();
        $this->assertTrue(in_array($aluno->alu_id, $pluck));
        $this->assertEquals($aluno->alu_id, $response->last()->alu_id);
    }
}
