<?php

use Tests\ModulosTestCase;
use Modulos\Geral\Models\Pessoa;
use Modulos\Geral\Models\Documento;
use Modulos\Academico\Models\Professor;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Modulos\Academico\Repositories\ProfessorRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProfessorRepositoryTest extends ModulosTestCase
{
    use DatabaseTransactions,
        WithoutMiddleware;

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
            'field' => 'pes_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response[0]->pes_id);
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
                'type' => 'like',
                'term' => 'Irineu'
            ],
            [
                'field' => 'pes_cpf',
                'type' => '=',
                'term' => '123456789'
            ],
        ];

        $response = $this->repo->paginate(null, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertCount(1, $response);
    }

    public function testPaginateWithSearchAndOrder()
    {
        factory(Professor::class, 2)->create();

        $sort = [
            'field' => 'prf_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'prf_id',
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
        factory(Professor::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'pes_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testCreate()
    {
        $response = factory(Professor::class)->create();

        $data = $response->toArray();

        $this->assertInstanceOf(Professor::class, $response);

        $this->assertArrayHasKey('prf_id', $data);
    }

    public function testFind()
    {
        $data = factory(Professor::class)->create();

        $this->assertDatabaseHas('acd_professores', $data->toArray());
    }

    public function testUpdate()
    {
        $data = factory(Professor::class)->create();

        $updateArray = $data->toArray();
        $updateArray['prf_pes_id'] = factory(Modulos\Geral\Models\Pessoa::class)->create([
            'pes_nome' => 'abc123'
        ])->pes_id;

        $professorId = $updateArray['prf_id'];
        unset($updateArray['prf_id']);

        $response = $this->repo->update($updateArray, $professorId, 'prf_id');

        $this->assertEquals(1, $response);
    }

    public function testDelete()
    {
        $data = factory(Professor::class)->create();
        $professorId = $data->prf_id;

        $response = $this->repo->delete($professorId);

        $this->assertEquals(1, $response);
    }

    public function testLists()
    {
        $entries = factory(Professor::class, 2)->create();

        $model = new Professor();
        $result = $model->join('gra_pessoas', 'pes_id', '=', 'prf_pes_id');

        $expected = $result->pluck('pes_nome', 'prf_pes_id');

        $fromRepository = $this->repo->lists('prf_id', 'pes_nome', false);

        $this->assertEquals($expected, $fromRepository);
    }
}
