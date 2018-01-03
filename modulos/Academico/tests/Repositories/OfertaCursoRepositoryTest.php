<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modulos\Academico\Repositories\OfertaCursoRepository;
use Modulos\Academico\Models\OfertaCurso;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Academico\Models\Turma;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;

class OfertaCursoRepositoryTest extends TestCase
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

    public function login()
    {
        $user = factory(Modulos\Seguranca\Models\Usuario::class)->create();
        $this->be($user);
    }

    public function setUp()
    {
        parent::setUp();

        Artisan::call('modulos:migrate');

        $this->repo = $this->app->make(OfertaCursoRepository::class);
        $this->login();
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

    public function testCreate()
    {
        $data = $this->mockData();
        $ofertacurso = $this->repo->create($data);

        $this->assertInstanceOf(OfertaCurso::class, $ofertacurso);

        $this->assertArrayHasKey('ofc_id', $ofertacurso);
    }

    public function testCreateReturnNull()
    {
        $data = factory(Modulos\Academico\Models\OfertaCurso::class)->create();
        $data = ['ofc_ano' => $data->ofc_ano,
                 'ofc_mdl_id' => $data->ofc_mdl_id,
                 'ofc_crs_id' => $data->ofc_crs_id,
                 'ofc_mtc_id' => $data->ofc_mtc_id,
                 'polos' => null
               ];
        $ofertacurso = $this->repo->create($data);

        $this->assertEquals(null, $ofertacurso);
    }

    public function testFind()
    {
        $data = factory(Modulos\Academico\Models\OfertaCurso::class)->create();

        $this->assertDatabaseHas('acd_ofertas_cursos', $data->toArray());
    }

    public function testUpdate()
    {
        $data = factory(Modulos\Academico\Models\OfertaCurso::class)->create();

        $updateArray = $data->toArray();
        $updateArray['ofc_ano'] = 1999;
        $updateArray['polos'] = null;

        $ofertacursodId = $updateArray['ofc_id'];
        unset($updateArray['ofc_id']);

        $response = $this->repo->update($updateArray, $ofertacursodId, 'ofc_id');

        $this->assertEquals(1, $response);
    }

    public function testUpdateRemovePolos()
    {
        $data = $this->mockData();
        $ofertacurso = $this->repo->create($data);

        $ofertacursodId = $ofertacurso->ofc_id;
        $data['polos'] = null;
        unset($data['ofc_id']);
        $response = $this->repo->update($data, $ofertacursodId, 'ofc_id');

        $this->assertEquals(1, $response);
    }

    public function testUpdateWithPolos()
    {
        $data = factory(Modulos\Academico\Models\OfertaCurso::class)->create();
        factory(Modulos\Academico\Models\Turma::class, 4)->create(['trm_ofc_id' => $data->ofc_id]);

        $updateArray = $data->toArray();
        $updateArray['ofc_ano'] = 1999;
        $updateArray['polos'] = [];
        $polosnovos = factory(\Modulos\Academico\Models\Polo::class, 3)->create();
        foreach ($polosnovos as $key => $polo) {
            $updateArray['polos'][] = $polo;
        }

        $ofertacursodId = $updateArray['ofc_id'];
        unset($updateArray['ofc_id']);

        $response = $this->repo->update($updateArray, $ofertacursodId, 'ofc_id');

        $this->assertEquals(1, $response);
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


    public function testDelete()
    {
        $data = factory(OfertaCurso::class)->create();
        $ofertacursoId = $data->ofc_id;

        $response = $this->repo->delete($ofertacursoId);

        $this->assertEquals(1, $response);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
