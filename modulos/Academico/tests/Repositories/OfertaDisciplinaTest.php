<?php


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Artisan;
use Modulos\Academico\Models\OfertaDisciplina;
use Modulos\Academico\Repositories\OfertaDisciplinaRepository;

class OfertaDisciplinaTest extends TestCase
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

        $this->repo = $this->app->make(OfertaDisciplinaRepository::class);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testCreate()
    {
        $response = factory(OfertaDisciplina::class)->create();

        $data = $response->toArray();

        $this->assertInstanceOf(OfertaDisciplina::class, $response);

        $this->assertArrayHasKey('ofd_id', $data);
    }

    public function testFind()
    {
        $data = factory(OfertaDisciplina::class)->create();
        $data = $data->toArray();
        
        unset($data['ofd_tipo_avaliacao']);

        $this->seeInDatabase('acd_ofertas_disciplinas', $data);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
