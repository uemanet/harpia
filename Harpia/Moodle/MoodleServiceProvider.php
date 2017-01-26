<?php

namespace Harpia\Moodle;

use Illuminate\Support\ServiceProvider;
use Modulos\Academico\Models\Curso;
use Modulos\Academico\Models\PeriodoLetivo;
use Modulos\Academico\Models\Turma;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\PeriodoLetivoRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Integracao\Models\AmbienteServico;
use Modulos\Integracao\Models\AmbienteTurma;
use Modulos\Integracao\Models\AmbienteVirtual;
use Modulos\Integracao\Repositories\AmbienteServicoRepository;
use Modulos\Integracao\Repositories\AmbienteTurmaRepository;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;

class MoodleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['Moodle'] = $this->app->share(function ($app) {
            $ambienteServicoRepository = new AmbienteServicoRepository(new AmbienteServico());
            $ambienteTurmaRepository = new AmbienteTurmaRepository(new AmbienteTurma());
            $ambienteVirtualRepository = new AmbienteVirtualRepository(new AmbienteVirtual());
            $cursoRepository = new CursoRepository(new Curso());
            $turmaRepository = new TurmaRepository(new Turma());
            $periodoLetivoRepository = new PeriodoLetivoRepository(new PeriodoLetivo());

            return new Moodle($ambienteServicoRepository,
                $ambienteTurmaRepository,
                $ambienteVirtualRepository,
                $turmaRepository,
                $cursoRepository,
                $periodoLetivoRepository);
        });
    }
}
