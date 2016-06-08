<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modulos\Seguranca\Repositories\ModuloRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ModuloRepositoryTest extends TestCase
{
    use DatabaseTransactions,
        WithoutMiddleware;

    protected $repo;
    protected $validArray;

    public function setUp()
    {
        parent::setUp();

        $this->repo = $this->app->make(ModuloRepository::class);

        $this->validArray = [
            'mod_nome' => 'Modulo test',
            'mod_descricao' => 'Descricao do modulo',
            'mod_icone' => 'fa fa-cog',
            'mod_ativo' => 1
        ];
    }

    public function testAll()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
    }

    public function testPaginateWithoutParameters()
    {
        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        $sort = [
            'field' => 'mod_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response[0]->mod_id);
    }

    public function testPaginateWithSearch()
    {
        $search = [
            [
                'field' => 'mod_nome',
                'type' => 'like',
                'term' => 'seguranca'
            ]
        ];

        $response = $this->repo->paginate(null, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertCount(1, $response);
    }

    public function testPaginateWithSearchAndOrder()
    {
        $sort = [
            'field' => 'mod_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'mod_id',
                'type' => '>',
                'term' => '0'
            ]
        ];

        $response = $this->repo->paginate($sort, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testPaginateRequest()
    {
        $requestParameters = [
            'page' => '1',
            'field' => 'mod_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testCreate()
    {
        $response = $this->saveData($this->validArray);

        $response = $response->toArray();

        $this->assertArrayHasKey('mod_id', $response);
    }

    public function testFind()
    {
        $response = $this->saveData($this->validArray);
        $moduloId = $response->mod_id;

        $response = $this->repo->find($moduloId);

        $this->assertInstanceOf(\Modulos\Seguranca\Models\Modulo::class, $response);
    }

    public function testUpdate()
    {
        $response = $this->saveData($this->validArray);

        $updateArray = $response->toArray();
        $updateArray['mod_nome'] = 'abcde_edcba';

        $moduloId = $updateArray['mod_id'];
        unset($updateArray['mod_id']);

        $response = $this->repo->update($updateArray, $moduloId, 'mod_id');

        $this->assertEquals(1, $response);
    }

    public function testDelete()
    {
        $response = $this->saveData($this->validArray);
        $moduloId = $response->mod_id;

        $response = $this->repo->delete($moduloId);

        $this->assertEquals(1, $response);
    }

    private function saveData($data)
    {
        return $this->repo->create($data);
    }
}