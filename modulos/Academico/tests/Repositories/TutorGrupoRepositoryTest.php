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

    public function testgetTiposTutoria()
    {
        $tutorgrupo = factory(\Modulos\Academico\Models\TutorGrupo::class)->create();
        $response = $this->repo->getTiposTutoria($tutorgrupo->grupo->grp_id);

        $this->assertNotEmpty($response);
    }

    public function testpaginateRequestByGrupo()
    {
        $tutorgrupo = factory(\Modulos\Academico\Models\TutorGrupo::class)->create();
        $response = $this->repo->paginateRequestByGrupo($tutorgrupo->grupo->grp_id);

        $this->assertNotEmpty($response);
    }

    public function testpaginateRequestByGrupoWithParameters()
    {
        $tutorgrupo = factory(\Modulos\Academico\Models\TutorGrupo::class)->create();
        $requestParameters['field'] = 'ttg_tut_id';
        $requestParameters['sort'] = 'desc';

        $response = $this->repo->paginateRequestByGrupo($tutorgrupo->grupo->grp_id, $requestParameters);

        $this->assertNotEmpty($response);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
