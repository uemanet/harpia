<?php

use Tests\ModulosTestCase;
use Modulos\Academico\Models\Turma;
use Stevebauman\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Integracao\Repositories\AmbienteTurmaRepository;

class TurmaRepositoryTest extends ModulosTestCase
{
    protected $AmbienteTurmarepo;

    public function setUp()
    {
        parent::setUp();
        $this->AmbienteTurmarepo = $this->app->make(AmbienteTurmaRepository::class);
        $this->repo = $this->app->make(TurmaRepository::class);
        $this->table = 'acd_turmas';
    }

    public function testCreate()
    {
        $data = factory(Turma::class)->raw();
        $entry = $this->repo->create($data);

        $this->assertInstanceOf(Turma::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->toArray());
    }

    public function testFind()
    {
        $entry = factory(Turma::class)->create();
        $id = $entry->trm_id;
        $fromRepository = $this->repo->find($id);

        $this->assertInstanceOf(Turma::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepository->toArray());
        $this->assertEquals($entry->toArray(), $fromRepository->toArray());
    }

    public function testUpdate()
    {
        $entry = factory(Turma::class)->create();
        $id = $entry->trm_id;

        $data = $entry->toArray();

        $data['trm_nome'] = "slug";

        $return = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(Turma::class, $fromRepository);
        $this->assertEquals($data, $fromRepository->toArray());
    }

    public function testDelete()
    {
        $entry = factory(Turma::class)->create();
        $id = $entry->trm_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        $entries = factory(Turma::class, 2)->create();

        $model = new Turma();
        $expected = $model->pluck('trm_nome', 'trm_id');
        $fromRepository = $this->repo->lists('trm_id', 'trm_nome');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        $entries = factory(Turma::class, 2)->create();

        factory(Turma::class)->create([
            'trm_nome' => 'turma'
        ]);

        $searchResult = $this->repo->search(array(['trm_nome', '=', 'turma']));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(Turma::class, 2)->create();

        $entry = factory(Turma::class)->create([
            'trm_nome' => "turma"
        ]);

        $expected = [
            'trm_id' => $entry->trm_id,
            'trm_nome' => $entry->trm_nome
        ];

        $searchResult = $this->repo->search(array(['trm_nome', '=', "turma"]), ['trm_id', 'trm_nome']);

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
        $created = factory(Turma::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(Turma::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new Turma();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Turma::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Turma::class, 2)->create();

        $sort = [
            'field' => 'trm_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->trm_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Turma::class, 2)->create();
        factory(Turma::class)->create([
            'trm_nome' => 'turma',
        ]);

        $search = [
            [
                'field' => 'trm_nome',
                'type' => '=',
                'term' => 'turma'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('turma', $response->first()->trm_nome);
    }

    public function testPaginateRequest()
    {
        factory(Turma::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'trm_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }

    public function testfindAllByOfertaCurso()
    {
        $turma = factory(Modulos\Academico\Models\Turma::class)->create();
        $response = $this->repo->findAllByOfertaCurso($turma->ofertacurso->ofc_id);
        $this->assertNotEmpty($response);
    }

    public function testfindAllByOfertaCursoIntegrada()
    {
        $oferta = factory(Modulos\Academico\Models\OfertaCurso::class)->create();
        $turma = factory(Modulos\Academico\Models\Turma::class)->create(['trm_ofc_id' => $oferta->ofc_id, 'trm_integrada' => 1]);
        $ambiente = factory(Modulos\Integracao\Models\AmbienteVirtual::class)->create();
        $dados['atr_trm_id'] = $turma->trm_id;
        $dados['atr_amb_id'] = $ambiente->amb_id;

        $this->AmbienteTurmarepo->create($dados);
        $response = $this->repo->findAllByOfertaCursoIntegrada($oferta->ofc_id);
        $this->assertNotEmpty($response);
    }

    public function testfindAllByOfertaCursoNaoIntegrada()
    {
        $turma = factory(Modulos\Academico\Models\Turma::class)->create(['trm_integrada' => 0]);
        $response = $this->repo->findAllByOfertaCursoNaoIntegrada($turma->ofertacurso->ofc_id);
        $this->assertNotEmpty($response);
    }

    public function testfindCursoByTurma()
    {
        $turma = factory(Modulos\Academico\Models\Turma::class)->create();
        $response = $this->repo->findCursoByTurma($turma->trm_id);
        $this->assertNotEmpty($response);
    }

    public function testlistsAllById()
    {
        $turma = factory(Modulos\Academico\Models\Turma::class)->create();
        $response = $this->repo->listsAllById($turma->trm_id);
        $this->assertNotEmpty($response);
    }

    public function testgetTurmaPolosByMatriculas()
    {
        $matricula = factory(Modulos\Academico\Models\Matricula::class)->create();
        $response = $this->repo->getTurmaPolosByMatriculas($matricula->turma->trm_id);
        $this->assertNotEmpty($response);
    }

    public function testfindAllWithVagasDisponiveisByOfertaCurso()
    {
        $matricula = factory(Modulos\Academico\Models\Matricula::class)->create();
        $response = $this->repo->findAllWithVagasDisponiveisByOfertaCurso($matricula->turma->ofertacurso->ofc_id);
        $this->assertNotEmpty($response);
    }

    public function testPaginatRequest()
    {
        $oferta = factory(Modulos\Academico\Models\OfertaCurso::class)->create();
        $turmas = factory(Modulos\Academico\Models\Turma::class, 10)->create(['trm_ofc_id' => $oferta->ofc_id]);

        $response = $this->repo->paginateRequestByOferta($oferta->ofc_id);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateRequestWithSort()
    {
        $oferta = factory(Modulos\Academico\Models\OfertaCurso::class)->create();
        $turmas = factory(Modulos\Academico\Models\Turma::class, 10)->create(['trm_ofc_id' => $oferta->ofc_id]);

        $sort = [
            'field' => 'trm_nome',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginateRequestByOferta($oferta->ofc_id, $sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertNotEmpty($response);
    }

    public function testgetCurso()
    {
        $turma = factory(Turma::class)->create();
        $response = $this->repo->getCurso($turma->trm_id);
        $this->assertNotEmpty($response);
    }

    public function testpendenciasTurmaReturnTrue()
    {
        $matricula = factory(Modulos\Academico\Models\Matricula::class)->create();
        $response = $this->repo->pendenciasTurma($matricula->mat_trm_id);
        $this->assertEquals($response, true);
    }

    public function testfullName()
    {
        $turma = factory(Turma::class)->create();

        $fullname = $this->repo->fullName($turma);

        $this->assertEquals($fullname, $turma->ofertacurso->curso->crs_nome . ' - ' . $turma->trm_nome . ' - ' . $turma->periodo->per_nome);
    }

    public function testshortName()
    {
        $turma = factory(Turma::class)->create();

        $shortname = $this->repo->shortName($turma);

        $this->assertEquals($shortname, str_replace(' ', '_', $turma->ofertacurso->curso->crs_sigla . ' ' . $turma->trm_nome . ' ' . $turma->periodo->per_nome));
    }

    public function testpendenciasTurmaReturnFalse()
    {
        $response = $this->repo->pendenciasTurma(1);
        $this->assertEquals($response, false);
    }
}
