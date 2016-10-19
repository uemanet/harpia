<?php

namespace Modulos\Academico\Http\Controllers;

use Modulos\Academico\Http\Requests\DisciplinaRequest;
use Modulos\Academico\Repositories\ModuloDisciplinaRepository;
use Modulos\Academico\Repositories\DisciplinaRepository;
use Modulos\Academico\Repositories\NivelCursoRepository;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Illuminate\Http\Request;

class ModulosDisciplinasController extends BaseController
{
    protected $modulodisciplinaRepository;
    protected $disciplinaRepository;

    public function __construct(ModuloDisciplinaRepository $modulodisciplinaRepository,DisciplinaRepository $disciplinaRepository)
    {
        $this->modulodisciplinaRepository = $modulodisciplinaRepository;
        $this->disciplinaRepository = $disciplinaRepository;

    }

    public function getIndex(Request $request)
    {
        $paginacao = null;
        $tabela = null;
        $tableData = null;

        if (!empty($request->all())) {
            $disciplinas = $this->disciplinaRepository->paginateRequest($request->all());
        } else {
            return view('Academico::vinculos.index', ['tabela' => $tabela, 'paginacao' => $paginacao]);
        }

            $paginacao = $tableData->appends($request->except('page'));

        return view('Academico::disciplinas.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }
}
