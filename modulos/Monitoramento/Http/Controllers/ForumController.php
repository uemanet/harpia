<?php

namespace Modulos\Monitoramento\Http\Controllers;

use Configuracao;
use App\Http\Controllers\Controller;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;

class ForumController extends Controller
{
    protected $cursoRepository;
    protected $ambientevirtualRepository;

    public function __construct(CursoRepository $cursoRepository, AmbienteVirtualRepository $ambientevirtualRepository)
    {
        $this->cursoRepository = $cursoRepository;
        $this->ambientevirtualRepository = $ambientevirtualRepository;
    }

    public function getIndex()
    {
        $ambientes = $this->ambientevirtualRepository->findAmbientesWithMonitor();

        return view('Monitoramento::forumresponse.index', compact('ambientes'));
    }

    public function getMonitorar($idAmbiente)
    {
        $ambientevirtual = $this->ambientevirtualRepository->find($idAmbiente);
        if (is_null($ambientevirtual)) {
            flash()->error('Ambiente não existe!');
            return redirect()->back();
        }

        $ambiente = $this->ambientevirtualRepository->findAmbienteWithMonitor($idAmbiente);

        $cursos = $this->cursoRepository->getCursosByAmbiente($idAmbiente);

        return view('Monitoramento::forumresponse.monitorar', compact('cursos', 'ambiente'));
    }
}
