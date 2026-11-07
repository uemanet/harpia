<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Schema;
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
        $_ENV['DB_CONNECTION'] = 'sqlite_testing';
        $_SERVER['DB_CONNECTION'] = 'sqlite_testing';
        $app = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap/app.php';
        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
        $app['config']->set('database.default', 'sqlite_testing');
        return $app;
    }

    /**
     * @see InteractsWithDatabase::assertDatabaseHas()
     * @param $table
     * @param array $data
     * @param null $connection
     * @return \TestCase
     */
    protected function assertDatabaseHas($table, array $data = [], $connection = null)
    {
        /*
         * Previne erros causados pela diferenca de tempo
         * entre a criacao do registro, sua edicao
         * e atualizacao no banco durante os testes
         */
        if (array_key_exists('updated_at', $data)) {
            unset($data['updated_at']);
        }

        return parent::assertDatabaseHas($table, $data, $connection);
    }

    protected static bool $migrated = false;

    public function setUp(): void
    {
        parent::setUp();

        if (!static::$migrated) {
            $this->app['db']->rollBack();
            $dbPath = config('database.connections.sqlite_testing.database');
            if ($dbPath && file_exists($dbPath) && $dbPath !== ':memory:') {
                unlink($dbPath);
                touch($dbPath);
                $this->app['db']->purge('sqlite_testing');
                $this->app['db']->reconnect('sqlite_testing');
            }
            Artisan::call('modulos:migrate');
            static::$migrated = true;
            $this->app['db']->beginTransaction();
        }
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }
}
