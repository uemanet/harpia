<?php

namespace Modulos\Monitoramento\Http\Controllers;

use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use App\Http\Controllers\Controller;
use Modulos\Academico\Repositories\CursoRepository;

/**
 * Class IndexController.
 */
class TempoOnlineController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    protected $cursoRepository;

    public function __construct(CursoRepository $cursoRepository)
    {
        $this->cursoRepository = $cursoRepository;
    }

    public function getIndex()
    {
        $cursos = $this->cursoRepository->lists('crs_id', 'crs_nome');
        return view('Monitoramento::tempoonline.index', compact('cursos'));
    }
}
