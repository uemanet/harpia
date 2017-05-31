<?php

namespace Modulos\Integracao\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modulos\Academico\Repositories\MatriculaOfertaDisciplinaRepository;
use Modulos\Academico\Repositories\OfertaDisciplinaRepository;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Integracao\Events\MapearNotasEvent;
use Modulos\Integracao\Repositories\MapeamentoNotasRepository;

class MapeamentoNotas extends BaseController
{
    protected $mapeamentoNotasRepository;
    protected $matriculaOfertaDisciplinaRepository;
    protected $ofertaDisciplinaRepository;

    public function __construct(
        MapeamentoNotasRepository $mapeamentoNotasRepository,
        MatriculaOfertaDisciplinaRepository $matriculaOfertaDisciplinaRepository,
        OfertaDisciplinaRepository $ofertaDisciplinaRepository
    ) {
        $this->mapeamentoNotasRepository = $mapeamentoNotasRepository;
        $this->matriculaOfertaDisciplinaRepository = $matriculaOfertaDisciplinaRepository;
        $this->ofertaDisciplinaRepository = $ofertaDisciplinaRepository;
    }

    public function setMapeamentoNotas(Request $request)
    {
        $dados = json_decode($request->get('data'), true);

        $response = $this->mapeamentoNotasRepository->setMapeamentoNotas($dados);

        if (array_key_exists('error', $response)) {
            return new JsonResponse($response, 400, [], JSON_UNESCAPED_UNICODE);
        }

        return new JsonResponse($response, 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function mapearNotasTurma($ofertaId)
    {
        $ofertaDisciplina = $this->ofertaDisciplinaRepository->find($ofertaId);

        if (!$ofertaDisciplina) {
            return new JsonResponse(['error' => 'Oferta de Disciplina não existe.'], 400, [], JSON_UNESCAPED_UNICODE);
        }

        $matriculasOfertaDisciplina = $ofertaDisciplina->matriculasOfertasDisciplinas;

        if ($matriculasOfertaDisciplina->count()) {
            foreach ($matriculasOfertaDisciplina as $matricula) {
                $this->mapeamentoNotasRepository->mapearNotasAluno($matricula->mof_id);
            }

            return new JsonResponse(['msg' => 'Notas dos alunos mapeadas com sucesso.'], 200, [], JSON_UNESCAPED_UNICODE);
        }

        return new JsonResponse(['error' => 'Oferta de Disciplina não possui matriculas.'], 400, [], JSON_UNESCAPED_UNICODE);
    }
}
