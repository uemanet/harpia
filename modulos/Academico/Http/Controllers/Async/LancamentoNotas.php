<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modulos\Academico\Repositories\MatriculaOfertaDisciplinaRepository;
use Modulos\Academico\Repositories\OfertaDisciplinaRepository;
use Modulos\Core\Http\Controller\BaseController;

class LancamentoNotas extends BaseController
{
    protected $ofertaDisciplinaRepository;
    protected $matriculaOfertaDisciplinaRepository;

    public function __construct(
        OfertaDisciplinaRepository $ofertaDisciplinaRepository,
        MatriculaOfertaDisciplinaRepository $matriculaOfertaDisciplinaRepository
    ){
        $this->ofertaDisciplinaRepository = $ofertaDisciplinaRepository;
        $this->matriculaOfertaDisciplinaRepository = $matriculaOfertaDisciplinaRepository;

    }


    public function getTable(Request $request)
    {
        $ofertaId = $request->get('ofd_id', null);

        if ($ofertaId) {
            $ofertaDisciplina = $this->ofertaDisciplinaRepository->find($ofertaId);
            $matriculas = $ofertaDisciplina->matriculasOfertasDisciplinas;


            $configsCurso = $ofertaDisciplina->turma->ofertaCurso->curso->configuracoes;

            $configuracoesCurso = $configsCurso->mapWithKeys(function ($item) {
               return [$item->cfc_nome => $item->cfc_valor];
            });

            $configuracoesCurso = json_encode($configuracoesCurso, JSON_UNESCAPED_SLASHES & JSON_UNESCAPED_LINE_TERMINATORS & JSON_UNESCAPED_UNICODE);

            $html = view('Academico::lancamentonotas.ajax.table_notas', [
                'matriculas' => $matriculas,
                'tipoNota' => $ofertaDisciplina->ofd_tipo_avaliacao,
                'configuracoesCurso' => $configuracoesCurso,
            ]);

            return $html;
        }

        return new JsonResponse([], 200);
    }

    public function postNotas(Request $request)
    {
        $matricula = $this->matriculaOfertaDisciplinaRepository->find($request->get('mof_id'));

        if($matricula){
            $this->matriculaOfertaDisciplinaRepository->update($request->except('_token'), $matricula->mof_id);
            $matricula = $this->matriculaOfertaDisciplinaRepository->find($request->get('mof_id'));
            return new JsonResponse($matricula, 200, ['content-type' => 'application/json']);
        }

        return new JsonResponse(['message' => 'Matrícula não encontrada'], 204, ['content-type' => 'application/json']);
    }
}