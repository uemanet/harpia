<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;

class ResultadosFinaisRepositoryTest extends TestCase
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

        $this->repo = $this->app->make(\Modulos\Academico\Repositories\ResultadosFinaisRepository::class);
    }

    public function testCreate()
    {
        $this->expectException(\Exception::class);

        $this->repo->create([
           'data' => 'thing'
        ]);
    }

    public function testUpdate()
    {
        $this->expectException(\Exception::class);

        $this->repo->create([
           'data' => 'thing'
        ]);
    }
    public function testDelete()
    {
        $this->expectException(\Exception::class);

        $this->repo->create([
           'data' => 'thing'
        ]);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
