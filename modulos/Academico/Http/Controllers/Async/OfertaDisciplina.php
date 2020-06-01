<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modulos\Academico\Events\DeleteOfertaDisciplinaEvent;
use Modulos\Academico\Events\CreateOfertaDisciplinaEvent;
use Modulos\Academico\Repositories\MatriculaOfertaDisciplinaRepository;
use Modulos\Academico\Repositories\ModuloDisciplinaRepository;
use Modulos\Academico\Repositories\OfertaDisciplinaRepository;
use Modulos\Academico\Repositories\ProfessorRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Integracao\Repositories\SincronizacaoRepository;
use DB;

class OfertaDisciplina extends BaseController
{
    protected $ofertaDisciplinaRepository;
    protected $matriculaOfertaDisciplinaRepository;
    protected $turmaRepository;
    protected $ambienteRepository;
    protected $moduloDisciplinaRepository;
    protected $professorRepository;

    public function __construct(
        OfertaDisciplinaRepository $ofertaDisciplinaRepository,
        MatriculaOfertaDisciplinaRepository $matricula,
        TurmaRepository $turmaRepository,
        AmbienteVirtualRepository $ambienteRepository,
        ModuloDisciplinaRepository $moduloDisciplinaRepository,
        ProfessorRepository $professorRepository
    ) {
        $this->ofertaDisciplinaRepository = $ofertaDisciplinaRepository;
        $this->matriculaOfertaDisciplinaRepository = $matricula;
        $this->turmaRepository = $turmaRepository;
        $this->ambienteRepository = $ambienteRepository;
        $this->moduloDisciplinaRepository = $moduloDisciplinaRepository;
        $this->professorRepository = $professorRepository;
    }

    public function getFindallbycurso($cursoId)
    {
        $ofertadisciplina = $this->ofertaDisciplinaRepository->findAllByCurso($cursoId);

        return new JsonResponse($ofertadisciplina, 200);
    }

    public function getFindall(Request $request)
    {

        $retorno = $this->ofertaDisciplinaRepository->findAll($request->all(), [
            'dis_id',
            'dis_nome',
            'dis_carga_horaria',
            'dis_creditos',
            'ofd_qtd_vagas',
            'mdc_tipo_disciplina',
            'ofd_id',
            'ofd_tipo_avaliacao',
            'prf_id',
            'pes_nome',
        ]);

        for ($i = 0; $i < $retorno->count(); ++$i) {
            $qtdMatriculas = $this->matriculaOfertaDisciplinaRepository->getQuantMatriculasByOfertaDisciplina($retorno[$i]->ofd_id);
            $retorno[$i]->qtdMatriculas = $qtdMatriculas;

            if ($retorno[$i]->mof_tipo_matricula == 'matriculacomum') {
                $retorno[$i]->mof_tipo_matricula = 'Matrícula Comum';
                continue;
            }

            if ($retorno[$i]->mof_tipo_matricula == 'aproveitamento') {
                $retorno[$i]->mof_tipo_matricula = 'Aproveitamento de Estudos';
                continue;
            }

        }

        return new JsonResponse($retorno, 200);
    }

    public function postOferecerdisciplina(Request $request)
    {
        try {
            if (!$this->ofertaDisciplinaRepository->verifyDisciplinaTurmaPeriodo($request->ofd_trm_id, $request->ofd_per_id, $request->ofd_mdc_id)) {
                if ($request->input('ofd_qtd_vagas') < 1) {
                    return new JsonResponse(['error' => 'Quantidade de vagas insuficiente'], Response::HTTP_BAD_REQUEST, [], JSON_UNESCAPED_UNICODE);
                }

                $ofertadisciplina = $this->ofertaDisciplinaRepository->create($request->except('_token'));

                if (!$ofertadisciplina) {
                    return new JsonResponse(['error' => 'Erro ao tentar salvar'], Response::HTTP_BAD_REQUEST, [], JSON_UNESCAPED_UNICODE);
                }

                $turma = $this->turmaRepository->find($ofertadisciplina->ofd_trm_id);

                if ($turma->trm_integrada) {
                    event(new CreateOfertaDisciplinaEvent($ofertadisciplina));
                }

                return new JsonResponse(['message' => 'Disciplina ofertada com sucesso!'], Response::HTTP_OK);
            }


            return new JsonResponse(['error' => 'Disciplina já existente para esse periodo e turma.'], Response::HTTP_BAD_REQUEST, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            return new JsonResponse(['error' => 'Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.'], Response::HTTP_BAD_REQUEST, [], JSON_UNESCAPED_UNICODE);
        }
    }

    public function getTableOfertasDisciplinas(Request $request)
    {
        $dados = $request->all();

        $busca = [];
        foreach ($dados as $key => $value) {
            $busca[] = [$key, '=', $value];
        }

        $ofertas = \Modulos\Academico\Models\OfertaDisciplina::where($busca)->get();

        foreach ($ofertas as $key => $oferta) {
            $ofertas[$key]->ofd_quant_matriculados = $this->matriculaOfertaDisciplinaRepository
                ->getQuantMatriculasByOfertaDisciplina($oferta->ofd_id);
        }

        $html = view('Academico::ofertasdisciplinas.ajax.table_ofertas_disciplinas', compact('ofertas'))->render();

        return new JsonResponse(['html' => $html], 200);
    }

    public function getTableDisciplinasNaoOfertadas(Request $request)
    {
        $moduloId = $request->get('mdo_id');
        $turmaId = $request->get('ofd_trm_id');
        $periodoId = $request->get('ofd_per_id');

        if (!$moduloId || !$turmaId || !$periodoId) {
            return new JsonResponse(['error' => 'Parâmetros não suficientes'], 400);
        }

        $disciplinas = $this->moduloDisciplinaRepository
                            ->getAllDisciplinasNotOfertadasByModulo($moduloId, $turmaId, $periodoId);

        $professores = $this->professorRepository->lists('prf_id', 'pes_nome', true);

        $html = view('Academico::ofertasdisciplinas.ajax.table_disciplinas_nao_ofertadas', compact('disciplinas', 'professores'))->render();

        return new JsonResponse(['html' => $html], 200);
    }
}
