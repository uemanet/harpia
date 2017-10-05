<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modulos\Academico\Repositories\ListaSemturRepository;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Core\Http\Controller\BaseController;

class ListaSemtur extends BaseController
{
    protected $listaSemturRepository;
    protected $matriculaCursoRepository;

    public function __construct(ListaSemturRepository $listaSemturRepository, MatriculaCursoRepository $matriculaCursoRepository)
    {
        $this->listaSemturRepository = $listaSemturRepository;
        $this->matriculaCursoRepository = $matriculaCursoRepository;
    }

    public function getTableAddMatriculas(Request $request)
    {
        $lista = $this->listaSemturRepository->find($request->get('lst_id'));

        if (!$lista) {
            return new JsonResponse('Lista de Carteiras Estudantis não encontrada.', 404, [], JSON_UNESCAPED_UNICODE);
        }

        $matriculasLista = $this->listaSemturRepository->findAll($request->all(), ['pes_nome' => 'asc'], ['mat_id', 'pes_nome', 'trm_nome', 'pol_nome']);

        $matriculasOutLista = $this->listaSemturRepository->getMatriculasOutOfLista($request->get('lst_id'), $request->get('mat_trm_id'), $request->get('mat_pol_id'));

        $html = view('Academico::carteirasestudantis.ajax.matriculas', compact('lista', 'matriculasLista', 'matriculasOutLista'))->render();

        return new JsonResponse($html, 200);
    }

    public function getTableShowMatriculas($listaId, $turmaId)
    {
        $lista = $this->listaSemturRepository->find($listaId);

        if (!$lista) {
            return new JsonResponse('Lista de Carteiras Estudantis não encontrada.', 404, [], JSON_UNESCAPED_UNICODE);
        }

        $matriculas = $this->listaSemturRepository->findAll([
            'lst_id' => $listaId,
            'mat_trm_id' => $turmaId
        ], ['pes_nome' => 'asc'], ['mat_id', 'pes_nome', 'trm_nome', 'pol_nome']);

        $turma = \Modulos\Academico\Models\Turma::find($turmaId);

        $html = view('Academico::carteirasestudantis.ajax.show', compact('lista', 'matriculas', 'turma'))->render();

        return new JsonResponse($html, 200);
    }

    public function postIncluirMatriculasLista(Request $request)
    {
        $matriculas = $request->input('matriculas');
        $listaId = $request->input('lst_id');

        $lista = $this->listaSemturRepository->find($listaId);

        if (!$lista) {
            return new JsonResponse('Lista de Carteiras Estudantis não encontrada.', 404, [], JSON_UNESCAPED_UNICODE);
        }

        if (empty($matriculas)) {
            return new JsonResponse('Nenhuma matrícula enviada.', 404, [], JSON_UNESCAPED_UNICODE);
        }

        foreach ($matriculas as $id) {
            $matricula = $this->matriculaCursoRepository->find($id);

            if ($matricula && $matricula->mat_situacao == 'cursando' && $this->listaSemturRepository->validateMatricula($matricula)) {
                $lista->matriculas()->attach($matricula->mat_id);
            }
        }

        return new JsonResponse('Matrículas incluídas na lista com sucesso.', 200);
    }
}
