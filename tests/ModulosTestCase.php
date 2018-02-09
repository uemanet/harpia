<?php

namespace Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;

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

    /**
     * @see InteractsWithDatabase::assertDatabaseHas()
     */
    protected function assertDatabaseHas($table, array $data, $connection = null)
    {
        /*
         * Previne erros causados pela diferenca de tempo
         * entre a criacao do registro, sua edicao
         * e atualizacao no banco durante os testes
         */
        if (array_key_exists('updated_at', $data)) {
            unset($data['updated_at']);
        }

        return parent::assertDatabaseHas($table, $data);
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
