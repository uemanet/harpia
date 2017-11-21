<?php

namespace Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ModulosTestCase extends \TestCase
{
    use DatabaseTransactions,
        WithoutMiddleware;

    protected $repo;
    protected $table;

    public function createApplication()
    {
        putenv('DB_CONNECTION=sqlite_testing');
        $app = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap/app.php';
        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
        return $app;
    }

    public function setUp()
    {
        parent::setUp();
        Artisan::call('modulos:migrate');
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
