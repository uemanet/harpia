<?php


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Artisan;
use Modulos\Academico\Models\Aluno;
use Modulos\Academico\Repositories\AlunoRepository;
use Modulos\Geral\Models\Pessoa;

class AlunoRepositoryTest extends TestCase
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

    public function setUp()
    {
        parent::setUp();

        Artisan::call('modulos:migrate');

        $this->repo = $this->app->make(AlunoRepository::class);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
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

        $this->assertGreaterThan(1, $response[0]->alu_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Aluno::class, 2)->create();

        $pessoa = factory(Pessoa::class)->create([
            'pes_nome' => 'Antonio',
        ]);

        factory(Aluno::class)->create([
           'alu_pes_id' => $pessoa->pes_id
        ]);

        $search = [
            [
                'field' => 'pes_nome',
                'type' => 'like',
                'term' => 'Antonio'
            ]
        ];

        $response = $this->repo->paginate(null, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertCount(1, $response);
    }

    public function testPaginateWithSearchAndOrder()
    {
        factory(Aluno::class, 2)->create();

        $sort = [
            'field' => 'alu_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'alu_id',
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

    public function testCreate()
    {
        $response = factory(Aluno::class)->create();

        $data = $response->toArray();

        $this->assertInstanceOf(Aluno::class, $response);

        $this->assertArrayHasKey('alu_id', $data);
    }

    public function testFind()
    {
        $data = factory(Aluno::class)->create();

        $this->seeInDatabase('acd_alunos', $data->toArray());
    }

//    public function testUpdate()
//    {
//        $data = factory(Aluno::class)->create();
//
//        $updateArray = $data->toArray();
//        $updateArray['cen_nome'] = 'abcde_edcba';
//
//        $centroId = $updateArray['cen_id'];
//        unset($updateArray['cen_id']);
//
//        $response = $this->repo->update($updateArray, $centroId, 'cen_id');
//
//        $this->assertEquals(1, $response);
//    }

    public function testDelete()
    {
        $data = factory(Aluno::class)->create();
        $alunoId = $data->alu_id;

        $response = $this->repo->delete($alunoId);

        $this->assertEquals(1, $response);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
