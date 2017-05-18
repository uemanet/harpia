<?php

namespace Modulos\Academico\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use Modulos\Academico\Repositories\GrupoRepository;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Academico\Repositories\MatrizCurricularRepository;
use Modulos\Academico\Repositories\ModuloMatrizRepository;
use Modulos\Academico\Repositories\OfertaCursoRepository;
use Modulos\Academico\Repositories\OfertaDisciplinaRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Academico\Repositories\TutorGrupoRepository;
use Modulos\Academico\Repositories\VinculoRepository;
use Modulos\Academico\Repositories\AlunoRepository;

class Vinculo
{
    private $vinculoRepository;
    private $matrizCurricularRepository;
    private $ofertaCursoRepository;
    private $turmaRepository;
    private $grupoRepository;
    private $moduloMatrizRepository;
    private $tutorGrupoRepository;
    private $alunoRepository;
    private $ofertaDisciplinaRepository;
    private $matriculaCursoRepository;

    private $defaultResponse;

    public function __construct(VinculoRepository $vinculoRepository,
                                MatrizCurricularRepository $matrizCurricularRepository,
                                OfertaCursoRepository $ofertaCursoRepository,
                                TurmaRepository $turmaRepository,
                                GrupoRepository $grupoRepository,
                                ModuloMatrizRepository $moduloMatrizRepository,
                                TutorGrupoRepository $tutorGrupoRepository,
                                AlunoRepository $alunoRepository,
                                OfertaDisciplinaRepository $ofertaDisciplinaRepository,
                                MatriculaCursoRepository $matriculaCursoRepository)
    {
        $this->vinculoRepository            = $vinculoRepository;
        $this->matrizCurricularRepository   = $matrizCurricularRepository;
        $this->ofertaCursoRepository        = $ofertaCursoRepository;
        $this->turmaRepository              = $turmaRepository;
        $this->grupoRepository              = $grupoRepository;
        $this->moduloMatrizRepository       = $moduloMatrizRepository;
        $this->tutorGrupoRepository         = $tutorGrupoRepository;
        $this->alunoRepository              = $alunoRepository;
        $this->ofertaDisciplinaRepository   = $ofertaDisciplinaRepository;
        $this->matriculaCursoRepository     = $matriculaCursoRepository;
        $this->defaultResponse              = "Você não tem autorização para acessar este recurso. Contate o Administrador.";
    }

    public function handle($request, Closure $next)
    {
        $rota = $this->routeName($request);

        switch ($rota) {
            case "turmas":
                return $this->handleTurmas($request, $next);
                break;
            case "grupos":
                return $this->handleGrupos($request, $next);
                break;
            case "tutoresgrupos":
                return $this->handleTutoresGrupos($request, $next);
                break;
            case "alunos":
                return $this->handleAlunos($request, $next);
                break;
            case "matricularalunodisciplina":
                return $this->handleMatriculaDisciplina($request, $next);
                break;
            case "matriculasofertasdisciplinas":
                return $this->handleAsyncMatriculaDisciplina($request, $next);
                break;
            default:
                flash()->error($this->defaultResponse);
                return redirect()->back();
        }
    }

    private function routeName($request)
    {
        $route = explode('.', Route::currentRouteName());
        return $route[count($route) - 2];
    }

    private function actionName($request)
    {
        $route = explode('.', Route::currentRouteName());
        return array_pop($route);
    }

    private function routeParameters($request)
    {
        $url = explode('/', $request->getPathInfo());

        if ($url[2] == "async") {
            return array_slice($url, 5);
        }

        return array_slice($url, 4);
    }


    /**
     * Verifica e filtra os vinculos da rota Turmas
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\RedirectResponse
     */
    private function handleTurmas($request, Closure $next)
    {
        $id = $request->get('id');
        $action = $this->actionName($request);

        if (is_null($id)) {
            return $next($request);
        }

        if (($action == "index" || $action == "create") && $request->getMethod() == "GET") {
            $oferta = $this->ofertaCursoRepository->find($id);

            if (!$oferta) {
                return $next($request);
            }

            if ($this->vinculoRepository->userHasVinculo(Auth::user()->usr_id, $oferta->ofc_crs_id)) {
                return $next($request);
            }

            flash()->error($this->defaultResponse);

            return redirect()->route('academico.ofertascursos.index');
        }

        $curso = $this->turmaRepository->getCurso($id);

        if (!$curso) {
            return $next($request);
        }

        if ($this->vinculoRepository->userHasVinculo(Auth::user()->usr_id, $curso)) {
            return $next($request);
        }

        flash()->error($this->defaultResponse);

        return redirect()->route('academico.ofertascursos.index');
    }

    /**
     * Verifica e filtra os vinculos da rota Grupos
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\RedirectResponse
     */
    private function handleGrupos($request, Closure $next)
    {
        $id = $request->get('id');
        $action = $this->actionName($request);

        if (is_null($id)) {
            return $next($request);
        }

        if (($action == "index" || $action == "create") && $request->getMethod() == "GET") {
            $curso = $this->turmaRepository->getCurso($id);

            if (!$curso) {
                return $next($request);
            }

            if ($this->vinculoRepository->userHasVinculo(Auth::user()->usr_id, $curso)) {
                return $next($request);
            }

            flash()->error($this->defaultResponse);
            return redirect()->route('academico.ofertascursos.index');
        }

        $grupo = $this->grupoRepository->find($id);

        if (!$grupo) {
            return $next($request);
        }

        $curso = $this->turmaRepository->getCurso($grupo->grp_trm_id);

        if ($this->vinculoRepository->userHasVinculo(Auth::user()->usr_id, $curso)) {
            return $next($request);
        }

        flash()->error($this->defaultResponse);
        return redirect()->route('academico.ofertascursos.index');
    }


    /**
     * Verifica e filtra os vinculos da rota Tutores Grupos
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    private function handleTutoresGrupos($request, Closure $next)
    {
        $id = $request->get('id');
        $action = $this->actionName($request);

        if (is_null($id)) {
            return $next($request);
        }

        if (($action == "index" || $action == "create") && $request->getMethod() == "GET") {
            $grupo = $this->grupoRepository->find($id);

            if (!$grupo) {
                return $next($request);
            }

            $curso = $this->turmaRepository->getCurso($grupo->grp_trm_id);

            if (!$curso) {
                flash()->error($this->defaultResponse);
                return redirect()->route('academico.ofertascursos.index');
            }


            if ($this->vinculoRepository->userHasVinculo(Auth::user()->usr_id, $curso)) {
                return $next($request);
            }

            flash()->error($this->defaultResponse);
            return redirect()->route('academico.ofertascursos.index');
        }

        $tutorGrupo = $this->tutorGrupoRepository->find($id);
        if (!$tutorGrupo) {
            return $next($request);
        }
        $grupo = $this->grupoRepository->find($tutorGrupo->ttg_grp_id);
        $curso = $this->turmaRepository->getCurso($grupo->grp_trm_id);

        if ($this->vinculoRepository->userHasVinculo(Auth::user()->usr_id, $curso)) {
            return $next($request);
        }

        flash()->error($this->defaultResponse);
        return redirect()->route('academico.ofertascursos.index');
    }

    /**
     * Verifica e filtra os vinculos na rota de alunos
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\RedirectResponse
     */
    private function handleAlunos($request, Closure $next)
    {
        $id = $request->id;
        $action = $this->actionName($request);

        if (is_null($id) || ($action == "create")) {
            return $next($request);
        }

        if (($action == "edit") || ($action == "show")) {
            $cursos = $this->alunoRepository->getCursos($id);

            // Aluno nao esta matriculado em curso algum
            if (empty($cursos)) {
                return $next($request);
            }

            // Verifica todos os cursos do aluno e o vinculo do usuario atual com cada um destes
            foreach ($cursos as $key => $value) {
                if ($this->vinculoRepository->userHasVinculo(Auth::user()->usr_id, $value)) {
                    return $next($request);
                }
            }
        }

        flash()->error($this->defaultResponse);
        return redirect()->route('academico.alunos.index');
    }

    /**
     * Verifica e filtra os vinculos na rota de matriculas ofertas disciplinas
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\RedirectResponse
     */
    private function handleMatriculaDisciplina($request, Closure $next)
    {
        $id = $request->id;
        $action = $this->actionName($request);

        if (is_null($id) || ($action == "index")) {
            return $next($request);
        }

        if ($action == "show") {
            $cursos = $this->alunoRepository->getCursos($id);

            // Aluno nao esta matriculado em curso algum
            if (empty($cursos)) {
                flash()->error($this->defaultResponse);
                return redirect()->route('academico.matriculasofertasdisciplinas.index');
            }

            // Verifica todos os cursos do aluno e o vinculo do usuario atual com cada um destes
            foreach ($cursos as $key => $value) {
                if ($this->vinculoRepository->userHasVinculo(Auth::user()->usr_id, $value)) {
                    return $next($request);
                }
            }
        }

        flash()->error($this->defaultResponse);
        return redirect()->route('academico.matriculasofertasdisciplinas.index');
    }

    /**
     * Verifica e filtra os vinculos no Async de matriculas ofertas disciplinas
     * @param $request
     * @param $next
     * @return mixed
     */
    public function handleAsyncMatriculaDisciplina($request, $next)
    {
        $routeName = $request->route()->getName();

        if ($routeName == 'academico.async.matriculasofertasdisciplinas.getmatriculaslote') {
            return $next($request);
        }

        if ($request->getMethod() == "GET") {
            // Pega os parametros via rota
            // id Aluno + id Curso + id Periodo
            $parameters = $this->routeParameters($request);

            $cursos = $this->alunoRepository->getCursos($parameters[0]);

            // Aluno nao esta matriculado em curso algum
            if (empty($cursos)) {
                // Sem autorizacao para o recurso
                return new JsonResponse($this->defaultResponse, Response::HTTP_BAD_REQUEST, [], JSON_UNESCAPED_UNICODE);
            }

            // Verifica todos os cursos do aluno e o vinculo do usuario atual com cada um destes
            foreach ($cursos as $key => $value) {
                if ($this->vinculoRepository->userHasVinculo(Auth::user()->usr_id, $value)) {
                    return $next($request);
                }
            }
        }

        if ($request->getMethod() == "POST") {
            $parameters = $request->all();
            $ofertas    = isset($parameters["ofertas"]) ? $parameters['ofertas'] : null;
            $matriculaId  = isset($parameters["mof_mat_id"]) ? $parameters['mof_mat_id'] : null;

            if ($routeName == 'academico.async.matriculasofertasdisciplinas.matriculaslote') {
                $matriculaId = $parameters['matriculas'][0];
                $ofertas[] = $parameters['ofd_id'];
            }

            // Verifica o vinculo na matricula
            $matricula = $this->matriculaCursoRepository->find($matriculaId);
            $curso = $this->turmaRepository->getCurso($matricula->mat_trm_id);

            if (!$this->vinculoRepository->userHasVinculo(Auth::user()->usr_id, $curso)) {
                // Sem autorizacao para o recurso
                return new JsonResponse($this->defaultResponse, Response::HTTP_BAD_REQUEST, [], JSON_UNESCAPED_UNICODE);
            }

            // Verifica os vinculos nas ofertas
            foreach ($ofertas as $key => $value) {
                $ofertaDisciplina = $this->ofertaDisciplinaRepository->find($value);
                $curso = $this->turmaRepository->getCurso($ofertaDisciplina->ofd_trm_id);

                if ($this->vinculoRepository->userHasVinculo(Auth::user()->usr_id, $curso)) {
                    return $next($request);
                }
            }
        }

        // Sem autorizacao para o recurso
        return new JsonResponse($this->defaultResponse, Response::HTTP_BAD_REQUEST, [], JSON_UNESCAPED_UNICODE);
    }
}
