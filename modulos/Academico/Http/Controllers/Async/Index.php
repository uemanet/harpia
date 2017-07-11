<?php

namespace Modulos\Academico\Http\Controllers\Async;

use DB;
use Illuminate\Http\JsonResponse;
use Modulos\Academico\Repositories\AlunoRepository;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Core\Http\Controller\BaseController;

class Index extends BaseController
{
    private $alunoRepository;
    private $matriculaRepository;
    private $cursoRepository;
    private $turmaRepository;

    private $meses = [
        1 => "Janeiro",
        2 => "Fevereiro",
        3 => "Março",
        4 => "Abril",
        5 => "Maio",
        6 => "Junho",
        7 => "Julho",
        8 => "Agosto",
        9 => "Setembro",
        10 => "Outubro",
        11 => "Novembro",
        12 => "Dezembro",
    ];

    public function __construct(AlunoRepository $alunoRepository,
                                MatriculaCursoRepository $matriculaCursoRepository,
                                CursoRepository $cursoRepository,
                                TurmaRepository $turmaRepository)
    {
        $this->alunoRepository = $alunoRepository;
        $this->matriculaRepository = $matriculaCursoRepository;
        $this->cursoRepository = $cursoRepository;
        $this->turmaRepository = $turmaRepository;
    }

    public function getCursoPorNivelData()
    {
        $result = DB::table('acd_cursos')
            ->select('nvc_nome', 'crs_nvc_id', DB::raw("COUNT(*) as quantidade"))
            ->join('acd_niveis_cursos', 'crs_nvc_id', '=', 'nvc_id')
            ->groupBy('crs_nvc_id')->get()->toArray();

        return new JsonResponse($result, 200);
    }

    public function getMatriculaPorStatusData()
    {
        $result = DB::table('acd_matriculas')
            ->select('mat_situacao', DB::raw("COUNT(*) as quantidade"))
            ->groupBy('mat_situacao')->get()->toArray();

        foreach ($result as $key => $item) {
            $result[$key]->mat_situacao = ucfirst($result[$key]->mat_situacao);
        }

        return new JsonResponse($result, 200);
    }

    public function getMatriculasPorMes()
    {
        $result = DB::table('acd_matriculas')
            ->select(DB::raw('MONTH(created_at) as mes'), DB::raw('COUNT(*) as quantidade'))
            ->where(DB::raw('created_at'), '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 6 MONTH)'))
            ->groupBy('mes')->get()->toArray();

        foreach ($result as $key => $item) {
            $result[$key]->mes = $this->meses[$result[$key]->mes];
        }

        return new JsonResponse($result, 200);
    }
}
