<?php

use Tests\ModulosTestCase;
use Illuminate\Support\Facades\DB;
use Modulos\Seguranca\Models\Usuario;
use Modulos\Academico\Models\OfertaCurso;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Academico\Repositories\OfertaCursoRepository;

class OfertaCursoRepositoryTest extends ModulosTestCase
{
    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(Usuario::class)->create();
        $this->repo = $this->app->make(OfertaCursoRepository::class);
        $this->table = 'acd_ofertas_cursos';
    }

    public function testCreate()
    {
        // Oferta sem polo
        $data = factory(OfertaCurso::class)->raw();
        $entry = $this->repo->create($data);

        $this->assertInstanceOf(OfertaCurso::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->toArray());

        // Adiciona oferta com polos
        $polos = factory(\Modulos\Academico\Models\Polo::class, 2)->create();
        $polosId = $polos->pluck('pol_id')->toArray();

        $data = factory(OfertaCurso::class)->raw();
        $data['polos'] = $polosId;
        $entry = $this->repo->create($data);

        $polosOfertasCurso = DB::table('acd_polos_ofertas_cursos')->get()->toArray();

        $ofertasFromDatabase[] = (string)$polosOfertasCurso[0]->poc_ofc_id;
        $ofertasFromDatabase[] = (string)$polosOfertasCurso[1]->poc_ofc_id;

        $polosFromDatabase[] = $polosOfertasCurso[0]->poc_pol_id;
        $polosFromDatabase[] = $polosOfertasCurso[1]->poc_pol_id;

        // Dois polos para a ultima oferta
        $ofertasIdExpected = [
            2,
            2
        ];

        $this->assertInstanceOf(OfertaCurso::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->toArray());

        $this->assertEquals($polosId, $polosFromDatabase);
        $this->assertEquals($ofertasIdExpected, $ofertasFromDatabase);

        // Tenta criar uma oferta com mesmo ano, modalidade e curso : Deve retornar Null

        $entry = $this->repo->create($data);
        $this->assertNull($entry);
    }

    public function testFind()
    {
        $entry = factory(OfertaCurso::class)->create();
        $id = $entry->ofc_id;
        $fromRepository = $this->repo->find($id);

        $this->assertInstanceOf(OfertaCurso::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepository->toArray());
        $this->assertEquals($entry->toArray(), $fromRepository->toArray());
    }

    public function testUpdate()
    {
        $entry = factory(Modulos\Academico\Models\OfertaCurso::class)->create();

        $data = $entry->toArray();
        $data['ofc_ano'] = 1999;

        $id = $entry->ofc_id;
        $response = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        $this->assertEquals(1, $response);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(OfertaCurso::class, $fromRepository);
        $this->assertEquals($data, $fromRepository->toArray());
    }

    public function testUpdateRemovePolos()
    {
        $polos = factory(\Modulos\Academico\Models\Polo::class, 2)->create();
        $polosId = $polos->pluck('pol_id')->toArray();

        $data = factory(OfertaCurso::class)->raw();
        $data['ofc_ano'] = 1999;
        $data['polos'] = $polosId;
        $entry = $this->repo->create($data);

        $data = $entry->toArray();

        $this->assertEquals(2, DB::table('acd_polos_ofertas_cursos')->get()->count());

        $id = $entry->ofc_id;
        $toCompare = $data;
        $data['polos'] = [];
        unset($toCompare['polos']);

        $response = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        $this->assertEquals(1, $response);
        $this->assertDatabaseHas($this->table, $toCompare);
        $this->assertInstanceOf(OfertaCurso::class, $fromRepository);
        $this->assertEquals($toCompare, $fromRepository->toArray());
        $this->assertEquals(0, DB::table('acd_polos_ofertas_cursos')->get()->count());
    }

    public function testUpdateWithPolos()
    {
        $polos = factory(\Modulos\Academico\Models\Polo::class, 2)->create();
        $polosId = $polos->pluck('pol_id')->toArray();

        $data = factory(OfertaCurso::class)->raw();
        $data['polos'] = $polosId;
        $entry = $this->repo->create($data);

        $data = $entry->toArray();

        $this->assertEquals(2, DB::table('acd_polos_ofertas_cursos')->get()->count());

        $polosId[] = factory(\Modulos\Academico\Models\Polo::class)->create()->pol_id;

        $id = $entry->ofc_id;
        $toCompare = $data;
        $data['polos'] = $polosId;
        unset($toCompare['polos']);

        $response = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        $this->assertEquals(1, $response);
        $this->assertDatabaseHas($this->table, $toCompare);
        $this->assertInstanceOf(OfertaCurso::class, $fromRepository);
        $this->assertEquals($toCompare, $fromRepository->toArray());
        $this->assertEquals(3, DB::table('acd_polos_ofertas_cursos')->get()->count());
    }

    public function testUpdateWithPolosEnroled()
    {
        $polos = factory(\Modulos\Academico\Models\Polo::class, 2)->create();
        $polosId = $polos->pluck('pol_id')->toArray();

        // Oferta de curso com polos
        $data = factory(OfertaCurso::class)->raw();
        $data['polos'] = $polosId;
        $entry = $this->repo->create($data);

        $data = $entry->toArray();

        $this->assertEquals(2, DB::table('acd_polos_ofertas_cursos')->get()->count());

        $polosId[] = factory(\Modulos\Academico\Models\Polo::class)->create()->pol_id;

        // Duas turmas na oferta
        $turmas[] = factory(\Modulos\Academico\Models\Turma::class)->create([
            'trm_ofc_id' => $entry->ofc_id
        ]);

        $turmas[] = factory(\Modulos\Academico\Models\Turma::class)->create([
            'trm_ofc_id' => $entry->ofc_id
        ]);

        // Grupos
        factory(\Modulos\Academico\Models\Grupo::class)->create([
            'grp_trm_id' => $turmas[0]->trm_id,
            'grp_pol_id' => $polosId[0]
        ]);

        factory(\Modulos\Academico\Models\Grupo::class)->create([
            'grp_trm_id' => $turmas[1]->trm_id,
            'grp_pol_id' => $polosId[1]
        ]);

        // 1 - Atualizar removendo os polos
        $id = $entry->ofc_id;
        $data['ofc_ano'] = 1999;
        $toCompare = $data;

        unset($data['polos']);  // Remove polos
        unset($toCompare['polos']);

        $response = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        $databaseData = $fromRepository->toArray();

        /**
         * Previne sobre diferencas no campo updated_at
         */
        unset($toCompare['updated_at']);
        unset($databaseData['updated_at']);

        // Os polos nao devem ser removidos da oferta
        $this->assertEquals($toCompare, $databaseData);
        $this->assertTrue($response);
        $this->assertEquals(2, DB::table('acd_polos_ofertas_cursos')->get()->count());

        // 2 - Testa adicionando polos
        $data['polos'][] = factory(\Modulos\Academico\Models\Polo::class)->create()->pol_id;

        $response = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        $databaseData = $fromRepository->toArray();

        /**
         * Previne sobre diferencas no campo updated_at
         */
        unset($databaseData['updated_at']);

        // Um polo deve ser adicionado
        $this->assertTrue(is_array($response));
        $this->assertEquals('warning', $response['type']);
        $this->assertEquals($toCompare, $databaseData);
        $this->assertEquals(3, DB::table('acd_polos_ofertas_cursos')->get()->count());
    }

    public function testDelete()
    {
        $entry = factory(OfertaCurso::class)->create();
        $id = $entry->ofc_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testPaginateWithoutParameters()
    {
        $vinculos = factory(\Modulos\Academico\Models\Vinculo::class, 2)->create();

        foreach ($vinculos as $vinculo) {
            factory(OfertaCurso::class)->create([
                'ofc_crs_id' => $vinculo->ucr_crs_id,
                'ofc_mtc_id' => factory(Modulos\Academico\Models\MatrizCurricular::class)->create([
                    'mtc_crs_id' => $vinculo->ucr_crs_id
                ])->mtc_id,
            ]);
        }

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        $vinculos = factory(\Modulos\Academico\Models\Vinculo::class, 2)->create([
            'ucr_usr_id' => $this->user->usr_id
        ]);

        foreach ($vinculos as $vinculo) {
            factory(OfertaCurso::class)->create([
                'ofc_crs_id' => $vinculo->ucr_crs_id,
                'ofc_mtc_id' => factory(Modulos\Academico\Models\MatrizCurricular::class)->create([
                    'mtc_crs_id' => $vinculo->ucr_crs_id
                ])->mtc_id,
            ]);
        }

        $sort = [
            'field' => 'ofc_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSearch()
    {
        $this->actingAs($this->user);
        $vinculos = factory(\Modulos\Academico\Models\Vinculo::class, 2)->create();

        foreach ($vinculos as $vinculo) {
            factory(OfertaCurso::class)->create([
                'ofc_crs_id' => $vinculo->ucr_crs_id,
                'ofc_mtc_id' => factory(Modulos\Academico\Models\MatrizCurricular::class)->create([
                    'mtc_crs_id' => $vinculo->ucr_crs_id
                ])->mtc_id,
            ]);
        }


        $ofertaCurso = factory(\Modulos\Academico\Models\OfertaCurso::class)->create([
            'ofc_ano' => 2005
        ]);

        $vinculos = factory(\Modulos\Academico\Models\Vinculo::class)->create([
            'ucr_usr_id' => Auth::user()->usr_id,
            'ucr_crs_id' => $ofertaCurso->ofc_crs_id
        ]);

        $search = [
            [
                'field' => 'ofc_ano',
                'type' => 'like',
                'term' => '2005'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertCount(1, $response);
    }

    public function testPaginateWithSearchAndSort()
    {
        $vinculos = factory(\Modulos\Academico\Models\Vinculo::class, 2)->create();

        foreach ($vinculos as $vinculo) {
            factory(OfertaCurso::class)->create([
                'ofc_crs_id' => $vinculo->ucr_crs_id,
                'ofc_mtc_id' => factory(Modulos\Academico\Models\MatrizCurricular::class)->create([
                    'mtc_crs_id' => $vinculo->ucr_crs_id
                ])->mtc_id,
            ]);
        }

        $sort = [
            'field' => 'ofc_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'ofc_id',
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
        $vinculos = factory(\Modulos\Academico\Models\Vinculo::class, 2)->create();

        foreach ($vinculos as $vinculo) {
            factory(OfertaCurso::class)->create([
                'ofc_crs_id' => $vinculo->ucr_crs_id,
                'ofc_mtc_id' => factory(Modulos\Academico\Models\MatrizCurricular::class)->create([
                    'mtc_crs_id' => $vinculo->ucr_crs_id
                ])->mtc_id,
                'ofc_mdl_id' => 1,
                'ofc_ano' => 2005
            ]);
        }

        $requestParameters = [
            'page' => '1',
            'field' => 'ofc_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function mockData()
    {
        $polos = factory(\Modulos\Academico\Models\Polo::class, 3)->create();
        $curso = factory(\Modulos\Academico\Models\Curso::class)->create();
        $modalidade = factory(\Modulos\Academico\Models\Modalidade::class)->create();
        $matriz = factory(\Modulos\Academico\Models\MatrizCurricular::class)->create(['mtc_crs_id' => $curso->crs_id]);

        $arrayPolos = [];

        foreach ($polos as $key => $polo) {
            $arrayPolos[] = $polo->pol_id;
        }

        $data = ['ofc_ano' => 2018,
            'ofc_mdl_id' => $modalidade->mdl_id,
            'ofc_crs_id' => $curso->crs_id,
            'ofc_mtc_id' => $matriz->mtc_id,
            'polos' => $arrayPolos
        ];
        return $data;
    }

    public function testFindAllByCurso()
    {
        factory(OfertaCurso::class, 10);

        $curso = factory(\Modulos\Academico\Models\Curso::class)->create();

        $ofertacurso = factory(OfertaCurso::class, 3)->create([
            'ofc_crs_id' => $curso->crs_id
        ]);

        $response = $this->repo->findAllByCurso($curso->crs_id);
        $this->assertEquals(3, $response->count());
    }

    public function testFindAllByCursoWithoutPresencial()
    {
        factory(\Modulos\Academico\Models\Modalidade::class, 3)->create();
        $curso = factory(\Modulos\Academico\Models\Curso::class)->create();

        factory(OfertaCurso::class, 5)->create([
            'ofc_crs_id' => $curso->crs_id,
            'ofc_mdl_id' => 1
        ]);

        $response = $this->repo->findAllByCursoWithoutPresencial($curso->crs_id);
        $this->assertEquals(0, $response->count());

        $ofertasComEad = factory(OfertaCurso::class, 3)->create([
            'ofc_crs_id' => $curso->crs_id,
            'ofc_mdl_id' => random_int(2, 3)
        ]);

        $response = $this->repo->findAllByCursoWithoutPresencial($curso->crs_id);
        $this->assertEquals(3, $response->count());
    }

    public function testFindAllByCursowithoutEad()
    {
        factory(\Modulos\Academico\Models\Modalidade::class, 3)->create();
        $curso = factory(\Modulos\Academico\Models\Curso::class)->create();

        factory(OfertaCurso::class, 5)->create([
            'ofc_crs_id' => $curso->crs_id,
            'ofc_mdl_id' => random_int(2, 3)
        ]);

        $response = $this->repo->findAllByCursoWithoutEad($curso->crs_id);
        $this->assertEquals(0, $response->count());

        $ofertasComEad = factory(OfertaCurso::class, 3)->create([
            'ofc_crs_id' => $curso->crs_id,
            'ofc_mdl_id' => 1
        ]);

        $response = $this->repo->findAllByCursoWithoutEad($curso->crs_id);
        $this->assertEquals(3, $response->count());
    }

    public function testListsAllById()
    {
        $turma = factory(Modulos\Academico\Models\Turma::class)->create();

        $curso = $this->repo->listsAllById($turma->ofertacurso->curso->crs_id);

        $this->assertNotEmpty($curso, '');
    }
}
