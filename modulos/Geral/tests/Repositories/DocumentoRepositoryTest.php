<?php


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Artisan;
use Modulos\Geral\Repositories\DocumentoRepository;
use Carbon\Carbon;

class DocumentoRepositoryTest extends TestCase
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

        $this->repo = $this->app->make(DocumentoRepository::class);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Modulos\Geral\Models\Documento::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Modulos\Geral\Models\Documento::class, 2)->create();

        $sort = [
            'field' => 'doc_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(1, $response[0]->doc_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Modulos\Geral\Models\Documento::class, 2)->create();

        factory(Modulos\Geral\Models\Documento::class)->create([
            'doc_conteudo' => '05545376506',
        ]);

        $search = [
            [
                'field' => 'doc_conteudo',
                'type' => 'like',
                'term' => '05545376506'
            ]
        ];

        $response = $this->repo->paginate(null, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertCount(1, $response);
    }

    public function testPaginateWithSearchAndOrder()
    {
        factory(Modulos\Geral\Models\Documento::class, 2)->create();

        $sort = [
            'field' => 'doc_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'doc_id',
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
        factory(Modulos\Geral\Models\Documento::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'doc_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
    }

    public function testCreate()
    {
        $response = factory(Modulos\Geral\Models\Documento::class)->create();

        $data = $response->toArray();

        $this->assertInstanceOf(\Modulos\Geral\Models\Documento::class, $response);

        $this->assertArrayHasKey('doc_id', $data);
    }

    public function testFind()
    {
        $dados = factory(Modulos\Geral\Models\Documento::class)->create();

        $data = $dados->toArray();

        // Retorna para date format americano antes de comparar com o banco
        $data['doc_data_expedicao'] = Carbon::createFromFormat('d/m/Y', $data['doc_data_expedicao'])->toDateString();

        $this->seeInDatabase('gra_documentos', $data);
    }

    public function testUpdate()
    {
        $data = factory(Modulos\Geral\Models\Documento::class)->create();

        $updateArray = $data->toArray();
        $updateArray['doc_conteudo'] = '123456';

        $documentoId = $updateArray['doc_id'];
        unset($updateArray['doc_id']);

        $response = $this->repo->update($updateArray, $documentoId, 'doc_id');

        $this->assertEquals(1, $response);
    }

    public function testDelete()
    {
        $data = factory(Modulos\Geral\Models\Documento::class)->create();
        $documentoId = $data->doc_id;

        $response = $this->repo->delete($documentoId);

        $this->assertEquals(1, $response);
    }

    public function testExistsTipoDocumento()
    {
        $data = factory(Modulos\Geral\Models\Documento::class)->create();
        $documentoId = $data->doc_id;

        $response = $this->repo->verifyTipoExists($data->doc_tpd_id, $data->doc_pes_id);

        $this->assertEquals(false, $response);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
