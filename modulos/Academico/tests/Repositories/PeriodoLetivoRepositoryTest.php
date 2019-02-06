<?php

use Tests\ModulosTestCase;
use Modulos\Academico\Models\Turma;
use Modulos\Academico\Models\PeriodoLetivo;
use Stevebauman\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Academico\Repositories\PeriodoLetivoRepository;

class PeriodoLetivoRepositoryTest extends ModulosTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->repo = $this->app->make(PeriodoLetivoRepository::class);
        $this->table = 'acd_periodos_letivos';
    }

    public function testCreate()
    {
        $data = factory(PeriodoLetivo::class)->raw();
        $entry = $this->repo->create($data);

        $fromRepository = $entry->toArray();
        $fromRepository['per_fim'] = $entry->getOriginal('per_fim');
        $fromRepository['per_inicio'] = $entry->getOriginal('per_inicio');

        $this->assertInstanceOf(PeriodoLetivo::class, $entry);
        $this->assertDatabaseHas($this->table, $fromRepository);
    }

    public function testFind()
    {
        $entry = factory(PeriodoLetivo::class)->create();
        $id = $entry->per_id;
        $fromRepository = $this->repo->find($id);

        $fromRepositoryArray = $fromRepository->toArray();
        $fromRepositoryArray['per_fim'] = $fromRepository->getOriginal('per_fim');
        $fromRepositoryArray['per_inicio'] = $fromRepository->getOriginal('per_inicio');

        $entryArray = $entry->toArray();
        $entryArray['per_fim'] = $entry->getOriginal('per_fim');
        $entryArray['per_inicio'] = $entry->getOriginal('per_inicio');

        $this->assertInstanceOf(PeriodoLetivo::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepositoryArray);
        $this->assertEquals($entryArray, $fromRepositoryArray);
    }

    public function testUpdate()
    {
        $entry = factory(PeriodoLetivo::class)->create();
        $id = $entry->per_id;

        $data = $entry->toArray();

        $data['per_nome'] = "slug";
        $return = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        $data['per_fim'] = $entry->getOriginal('per_fim');
        $data['per_inicio'] = $entry->getOriginal('per_inicio');

        $fromRepositoryArray = $fromRepository->toArray();
        $fromRepositoryArray['per_fim'] = $fromRepository->getOriginal('per_fim');
        $fromRepositoryArray['per_inicio'] = $fromRepository->getOriginal('per_inicio');

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(PeriodoLetivo::class, $fromRepository);
        $this->assertEquals($data, $fromRepositoryArray);
    }

    public function testDelete()
    {
        $entry = factory(PeriodoLetivo::class)->create();
        $id = $entry->per_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        $entries = factory(PeriodoLetivo::class, 2)->create();

        $model = new PeriodoLetivo();
        $expected = $model->pluck('per_nome', 'per_id');
        $fromRepository = $this->repo->lists('per_id', 'per_nome');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        $entries = factory(PeriodoLetivo::class, 2)->create();

        factory(PeriodoLetivo::class)->create([
            'per_nome' => 'centro'
        ]);

        $searchResult = $this->repo->search(array(['per_nome', '=', 'centro']));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(PeriodoLetivo::class, 2)->create();

        $entry = factory(PeriodoLetivo::class)->create([
            'per_nome' => "centro"
        ]);

        $expected = [
            'per_id' => $entry->per_id,
            'per_nome' => $entry->per_nome
        ];

        $searchResult = $this->repo->search(array(['per_nome', '=', "centro"]), ['per_id', 'per_nome']);

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
        $created = factory(PeriodoLetivo::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(PeriodoLetivo::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new PeriodoLetivo();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(PeriodoLetivo::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(PeriodoLetivo::class, 2)->create();

        $sort = [
            'field' => 'per_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->per_id);
    }

    public function testPaginateWithSearch()
    {
        factory(PeriodoLetivo::class, 2)->create();
        factory(PeriodoLetivo::class)->create([
            'per_nome' => 'centro',
        ]);

        $search = [
            [
                'field' => 'per_nome',
                'type' => '=',
                'term' => 'centro'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('centro', $response->first()->per_nome);
    }

    public function testPaginateRequest()
    {
        factory(PeriodoLetivo::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'per_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }

    public function testGetAllByTurma()
    {
        $turmas = collect([]);
        $periodosLetivos = collect([]);

        // Períodos
        $periodosLetivos[] = factory(PeriodoLetivo::class)->create([
            'per_inicio' => '01/01/2016',
            'per_fim' => '01/06/2016'
        ]);

        $periodosLetivos[] = factory(PeriodoLetivo::class)->create([
            'per_inicio' => '04/06/2016',
            'per_fim' => '11/12/2016'
        ]);

        $periodosLetivos[] = factory(PeriodoLetivo::class)->create([
            'per_inicio' => '04/02/2017',
            'per_fim' => '01/06/2017'
        ]);

        $periodosLetivos[] = factory(PeriodoLetivo::class)->create([
            'per_inicio' => '04/06/2017',
            'per_fim' => '18/12/2017'
        ]);

        // Turmas
        for ($i = 0; $i < 3; $i++) {
            $turmas[] = factory(Turma::class)->create([
                'trm_per_id' => $periodosLetivos[$i]->per_id
            ]);
        }

        // Deve trazer todos os periodos para a primeira turma
        $result = $this->repo->getAllByTurma($turmas->first()->trm_id);

        $this->assertEquals(4, $result->count());
        $this->assertEquals($periodosLetivos->toArray(), $result->toArray());

        // Para a segunda turma deve trazer apenas os 3 ultimos periodos
        $result = $this->repo->getAllByTurma($turmas[1]->trm_id);

        $this->assertEquals(3, $result->count());
        $periodosLetivos->shift();
        $this->assertEquals($periodosLetivos->toArray(), $result->toArray());
    }

    public function testGetPeriodosValidos()
    {
        $periodosLetivos = collect([]);

        $currentYear = (int) date('Y', time());

        // Períodos
        $periodosLetivos[] = factory(PeriodoLetivo::class)->create([
            'per_inicio' => '01/01/' . $currentYear,
            'per_fim' => '01/06/' . $currentYear
        ]);

        $periodosLetivos[] = factory(PeriodoLetivo::class)->create([
            'per_inicio' => '04/06/' . $currentYear,
            'per_fim' => '31/12/' . $currentYear
        ]);

        $nextYear = $currentYear + 1;

        $periodosLetivos[] = factory(PeriodoLetivo::class)->create([
            'per_inicio' => '04/02/' . $nextYear,
            'per_fim' => '01/06/' . $nextYear
        ]);

        $periodosLetivos[] = factory(PeriodoLetivo::class)->create([
            'per_inicio' => '04/06/' . $nextYear,
            'per_fim' => '31/12/' . $nextYear
        ]);

        // Sao validos 4 periodos para o primeiro periodo letivo do ano corrente
        $result = $this->repo->getPeriodosValidos($currentYear, $periodosLetivos->first()->per_id);

        $expectedIds = $periodosLetivos->pluck('per_id')->toArray();

        $this->assertTrue(is_array($result));
        $this->assertEquals(4, count($result));
        $this->assertEquals($expectedIds, array_keys($result));
    }

    public function testVerifyNamePeriodo()
    {
        $periodosLetivos = factory(PeriodoLetivo::class, 10)->create();

        $result = $this->repo->verifyNamePeriodo('randomName');
        $this->assertFalse($result);

        $result = $this->repo->verifyNamePeriodo('randomName', random_int(1, 10));
        $this->assertFalse($result);

        $toCheck = $periodosLetivos->random();

        $result = $this->repo->verifyNamePeriodo($toCheck->per_nome);
        $this->assertTrue($result);

        $toCheck = $periodosLetivos->random();
        $result = $this->repo->verifyNamePeriodo($toCheck->per_nome, $toCheck->per_id);
        $this->assertFalse($result);
    }
}
