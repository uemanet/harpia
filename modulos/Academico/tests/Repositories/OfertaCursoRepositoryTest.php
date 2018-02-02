<?php

use Tests\ModulosTestCase;
use Illuminate\Support\Facades\DB;
use Modulos\Academico\Models\OfertaCurso;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Academico\Repositories\OfertaCursoRepository;

class OfertaCursoRepositoryTest extends ModulosTestCase
{
    public function setUp()
    {
        parent::setUp();
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
        $data = factory(Modulos\Academico\Models\OfertaCurso::class)->create();
        $turmas = factory(Modulos\Academico\Models\Turma::class, 4)->create(['trm_ofc_id' => $data->ofc_id]);

        $updateArray = $data->toArray();
        $updateArray['ofc_ano'] = 1999;
        $updateArray['polos'] = [];
        $polosnovos = factory(\Modulos\Academico\Models\Polo::class, 3)->create();
        foreach ($polosnovos as $key => $polo) {
            $updateArray['polos'][] = $polo;
            factory(Modulos\Academico\Models\Grupo::class, 4)->create(['grp_trm_id' => $turmas[0]->trm_id, 'grp_pol_id' => $polo]);
        }

        $ofertacursodId = $updateArray['ofc_id'];
        unset($updateArray['ofc_id']);

        $response = $this->repo->update($updateArray, $ofertacursodId, 'ofc_id');

        $this->assertEquals('warning', $response['type']);
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

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSearch()
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

    public function testPaginateWithSearchAndOrder()
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
        $ofertacurso = factory(Modulos\Academico\Models\OfertaCurso::class)->create();

        $curso = $this->repo->findAllByCurso($ofertacurso->curso->crs_id);

        $this->assertNotEmpty($curso, '');
    }

    public function testFindAllByCursowithoutpresencial()
    {
        $ofertacurso = factory(Modulos\Academico\Models\OfertaCurso::class, 2)->create();

        $curso = $this->repo->findAllByCursowithoutpresencial($ofertacurso[1]->curso->crs_id);

        $this->assertNotEmpty($curso, '');
    }

    public function testFindAllByCursowithoutEad()
    {
        $ofertacurso = factory(Modulos\Academico\Models\OfertaCurso::class, 2)->create();

        $curso = $this->repo->findAllByCursowithoutEad($ofertacurso[0]->curso->crs_id);

        $this->assertNotEmpty($curso, '');
    }

    public function testListsAllById()
    {
        $turma = factory(Modulos\Academico\Models\Turma::class)->create();

        $curso = $this->repo->listsAllById($turma->ofertacurso->curso->crs_id);

        $this->assertNotEmpty($curso, '');
    }
}
