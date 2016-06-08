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

    public function setUp()
    {
        parent::setUp();

        $this->repo = $this->app->make(ModuloRepository::class);
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
                'term' => 'academico'
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
}