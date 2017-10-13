<?php

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Artisan;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Carbon\Carbon;

class MatriculaCursoTest extends TestCase
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

        $this->repo = $this->app->make(MatriculaCursoRepository::class);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testCreate()
    {
        $response = factory(\Modulos\Academico\Models\Matricula::class)->create();

        $data = $response->toArray();

        $this->assertInstanceOf(\Modulos\Academico\Models\Matricula::class, $response);

        $this->assertArrayHasKey('mat_id', $data);
    }

    public function testFind()
    {
        $data = factory(\Modulos\Academico\Models\Matricula::class)->create();

        $data = $data->toArray();
        unset($data['mat_modo_entrada']);
        $data['mat_data_conclusao'] = Carbon::createFromFormat('d/m/Y', $data['mat_data_conclusao'])->toDateString();

        $this->assertDatabaseHas('acd_matriculas', $data);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
