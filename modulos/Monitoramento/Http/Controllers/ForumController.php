<?php

namespace Modulos\Monitoramento\Http\Controllers;

use Configuracao;
use App\Http\Controllers\Controller;
use function foo\func;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Integracao\Models\AmbienteVirtual;
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
        $ambientes = AmbienteVirtual::all()->filter(function ($value) {
            $servicos = $value->ambienteservico;

            // Retorna somente os ambientes com plugin de monitoramento configurado
            foreach ($servicos as $servico) {
                if ($servico->asr_ser_id == 1) {
                    return $value;
                }
            }
        });

        return view('Monitoramento::forumresponse.index', compact('ambientes'));
    }

    public function getMonitorar($idAmbiente)
    {
        $ambiente = $this->ambientevirtualRepository->find($idAmbiente);

        if (is_null($ambiente)) {
            flash()->error('Ambiente não existe!');
            return redirect()->back();
        }

        $servicos = $ambiente->ambienteservico;

        $monitoramento = $servicos->filter(function ($value) {
            if ($value->asr_ser_id == 1) {
                return $value;
            }
        })->first();

        $cursos = $this->cursoRepository->getCursosByAmbiente($idAmbiente);

        return view('Monitoramento::forumresponse.monitorar', compact('cursos', 'ambiente', 'monitoramento'));
    }
}
