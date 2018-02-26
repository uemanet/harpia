<?php

use Tests\ModulosTestCase;
use Modulos\Geral\Models\Pessoa;
use Modulos\Geral\Models\Documento;
use Modulos\Academico\Models\Professor;
use Illuminate\Database\Eloquent\Collection;
use Stevebauman\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Academico\Repositories\ProfessorRepository;

class ProfessorRepositoryTest extends ModulosTestCase
{
    protected $repo;

    public function setUp()
    {
        parent::setUp();
        $this->repo = $this->app->make(ProfessorRepository::class);
        $this->table = 'acd_professores';
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testCreate()
    {
        $data = factory(Professor::class)->raw();
        $entry = $this->repo->create($data);

        $this->assertInstanceOf(Professor::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->toArray());
    }

    public function testFind()
    {
        $entry = factory(Professor::class)->create();
        $id = $entry->prf_id;
        $fromRepository = $this->repo->find($id);

        $this->assertInstanceOf(Professor::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepository->toArray());
        $this->assertEquals($entry->toArray(), $fromRepository->toArray());
    }

    public function testUpdate()
    {
        $data = factory(Professor::class)->create();
        $id = $data->prf_id;

        $updateArray = $data->toArray();
        $updateArray['prf_pes_id'] = factory(Modulos\Geral\Models\Pessoa::class)->create([
            'pes_nome' => 'abc123'
        ])->pes_id;

        $professorId = $updateArray['prf_id'];

        $response = $this->repo->update($updateArray, $professorId, 'prf_id');
        $fromRepository = $this->repo->find($id);


        $this->assertEquals(1, $response);
        $this->assertDatabaseHas($this->table, $updateArray);
        $this->assertInstanceOf(Professor::class, $fromRepository);
        $this->assertEquals($updateArray, $fromRepository->toArray());
    }

    public function testDelete()
    {
        $entry = factory(Professor::class)->create();
        $id = $entry->prf_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        $entries = factory(Professor::class, 2)->create();

        $model = new Professor();
        $expected = $model->join('gra_pessoas', 'pes_id', '=', 'acd_professores.prf_pes_id')->pluck('pes_nome', 'prf_id');
        $fromRepository = $this->repo->lists('prf_id', 'pes_nome');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        factory(Professor::class, 2)->create();

        $pessoa = factory(Pessoa::class)->create([
            'pes_nome' => 'Irineu'
        ]);

        factory(Professor::class)->create([
            'prf_pes_id' => $pessoa->pes_id
        ]);

        $searchResult = $this->repo->search(array(['pes_nome', 'like', 'Irineu']));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(Professor::class, 2)->create();

        $pessoa = factory(Pessoa::class)->create([
            'pes_nome' => 'Irineu',
        ]);

        $entry = factory(Professor::class)->create([
            'prf_pes_id' => $pessoa->pes_id
        ]);

        $expected = [
            'prf_id' => $entry->prf_id,
            'pes_nome' => $entry->pessoa->pes_nome
        ];

        $searchResult = $this->repo->search(array(['pes_nome', '=', "Irineu"]), ['prf_id', 'pes_nome']);

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
        $created = factory(Professor::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(Professor::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new Professor();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Professor::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Professor::class, 2)->create();

        $sort = [
            'field' => 'prf_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan($response->last()->prf_id, $response->first()->prf_id); // 2, 1 || 1, 2
    }

    public function testPaginateWithSearch()
    {
        factory(Professor::class, 2)->create();

        $pessoa = factory(Pessoa::class)->create([
            'pes_nome' => 'Irineu',
        ]);

        $documento = factory(Documento::class)->create([
            'doc_pes_id' => $pessoa->pes_id,
            'doc_conteudo' => '123456789'
        ]);

        $entry = factory(Professor::class)->create([
            'prf_pes_id' => $pessoa->pes_id
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
        factory(Professor::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'prf_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }
}
