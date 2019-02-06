<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
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
     * @param $table
     * @param array $data
     * @param null $connection
     * @return \TestCase
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

    /**
     * @see TestCase::assertEquals()
     * @param $expected
     * @param $actual
     * @param string $message
     * @param float $delta
     * @param int $maxDepth
     * @param bool $canonicalize
     * @param bool $ignoreCase
     */
    public static function assertEquals($expected, $actual, string $message = '', float $delta = 0.0, int $maxDepth = 10, bool $canonicalize = false, bool $ignoreCase = false): void
    {
        /*
         * Previne erros causados pela diferenca de tempo
         * entre a criacao do registro, sua edicao
         * e atualizacao no banco durante os testes
         */
        if (is_array($expected) && is_array($actual)) {
            unset($actual['updated_at']);
            unset($expected['updated_at']);
        }

        parent::assertEquals($expected, $actual, $message, $delta, $maxDepth, $canonicalize, $ignoreCase);
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
