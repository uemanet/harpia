<?php

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Artisan;
use Modulos\Academico\Repositories\TutorGrupoRepository;
use Modulos\Academico\Models\TutorGrupo;
use Modulos\Academico\Models\Tutor;

class TutorGrupoRepositoryTest extends TestCase
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

        $this->repo = $this->app->make(TutorGrupoRepository::class);
    }


    public function testVerifyTutorExists()
    {
      $tutorgrupo = factory(Modulos\Academico\Models\TutorGrupo::class,10)->create();
      $retorno = $this->repo->verifyTutorExists(1);

      $this->assertEquals($retorno, true);
    }

    public function testHowManyTutors()
    {
      $tutorgrupo = factory(Modulos\Academico\Models\TutorGrupo::class,10)->create();
      $retorno = $this->repo->howManyTutors(1);
      $this->assertEquals($retorno, 1);
    }

    public function testVerifyTutorPresencial()
    {
      $tutorgrupo = factory(Modulos\Academico\Models\TutorGrupo::class,10)->create();

      $retorno = $this->repo->verifyTutorPresencial(1, 'presencial');

      $this->assertEquals($retorno, null);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }

}
