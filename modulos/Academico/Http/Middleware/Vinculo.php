<?php

namespace Modulos\Academico\Http\Middleware;

use Auth;
use Closure;
use Modulos\Academico\Repositories\MatrizCurricularRepository;
use Modulos\Academico\Repositories\OfertaCursoRepository;
use Modulos\Academico\Repositories\VinculoRepository;

class Vinculo
{
    private $vinculoRepository;
    private $matrizCurricularRepository;
    private $ofertaCursoRepository;

    public function __construct(VinculoRepository $vinculoRepository,
                                MatrizCurricularRepository $matrizCurricularRepository,
                                OfertaCursoRepository $ofertaCursoRepository)
    {
        $this->vinculoRepository = $vinculoRepository;
        $this->matrizCurricularRepository = $matrizCurricularRepository;
        $this->ofertaCursoRepository = $ofertaCursoRepository;
    }

    public function handle($request, Closure $next)
    {
        $rota = $this->routeName($request);

        switch ($rota) {
            case "cursos":
                return $this->handleCursos($request, $next);
                break;
            case "matrizescurriculares":
                return $this->handleMatrizesCurriculares($request, $next);
                break;
            case "ofertascursos":
                return $this->handleOfertasCursos($request, $next);
                break;
            case "turmas":
                return $this->handleTurmas($request, $next);
                break;
            case "grupos":
                return $this->handleGrupos($request, $next);
                break;
            case "tutoresgrupos":
                return $this->handleTutoresGrupos($request, $next);
                break;
            case "modulosmatrizes":
                return $this->handleModulosMatrizes($request, $next);
                break;
            default:
                flash()->error('Você não tem autorização para acessar este recurso. Contate o Administrador.');
                return redirect()->back();
        }
    }

    private function routeName($request)
    {
        $path = explode('/', $request->getPathInfo());
        return $path[2];
    }

    private function actionName($request)
    {
        $path = explode('/', $request->getPathInfo());
        return $path[3];
    }

    /**
     * Verifica e filtra os vinculos da rota cursos
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\RedirectResponse
     */
    private function handleCursos($request, Closure $next)
    {
        $cursoId = $request->id;

        if (is_null($cursoId)) {
            return $next($request);
        }

        if ($this->vinculoRepository->userHasVinculo(Auth::user()->usr_id, $cursoId)) {
            return $next($request);
        }

        flash()->error('Você não tem autorização para acessar este recurso. Contate o Administrador.');
        return redirect()->route('academico.cursos.index');
    }

    /**
     * Verifica e filtra os vinculos da rota Matrizes Curriculares
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\RedirectResponse
     */
    private function handleMatrizesCurriculares($request, Closure $next)
    {
        $id = $request->id;
        $action = $this->actionName($request);

        if(is_null($id)){
            return $next($request);
        }

        if (($action == "index" || $action == "create") && $request->getMethod() == "GET") {
            if ($this->vinculoRepository->userHasVinculo(Auth::user()->usr_id, $id)) {
                return $next($request);
            }

            flash()->error('Você não tem autorização para acessar este recurso. Contate o Administrador.');
            return redirect()->route('academico.cursos.index');
        }

        $matriz = $this->matrizCurricularRepository->find($id);

        if ($this->vinculoRepository->userHasVinculo(Auth::user()->usr_id, $matriz->mtc_crs_id)) {
            return $next($request);
        }

        flash()->error('Você não tem autorização para acessar este recurso. Contate o Administrador.');
        return redirect()->route('academico.cursos.index');
    }

    /**
     * Verifica e filtra os vinculos da rota Ofertas Cursos
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\RedirectResponse
     */
    private function handleOfertasCursos($request, Closure $next)
    {
        $id = $request->ofc_crs_id;

        if (is_null($id)) {
            return $next($request);
        }

        if ($this->vinculoRepository->userHasVinculo(Auth::user()->usr_id, $id)){
            return $next($request);
        }

        flash()->error('Você não tem autorização para acessar este recurso. Contate o Administrador.');
        return redirect()->route('academico.ofertascursos.index');
    }

    private function handleTurmas($request, Closure $next)
    {
        $id = $request->id;
        $action = $this->actionName($request);

    }

    private function handleGrupos($request, Closure $next)
    {
        return $next($request);
    }

    private function handleModulosMatrizes($request, Closure $next)
    {
        return $next($request);
    }

    private function handleTutoresGrupos($request, Closure $next)
    {
        return $next($request);
    }
}
