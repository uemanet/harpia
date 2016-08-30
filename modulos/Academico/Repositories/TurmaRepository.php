<?php

namespace Modulos\Academico\Repositories;

use Illuminate\Support\Facades\DB;
use Modulos\Academico\Models\Turma;
use Modulos\Core\Repository\BaseRepository;

class TurmaRepository extends BaseRepository
{
    public function __construct(Turma $turma)
    {
        $this->model = $turma;
    }

    public function findAllByCurso($cursoId)
    {
        $entries = DB::table('acd_cursos')
                        ->join('acd_ofertas_cursos', 'crs_id', '=', 'ofc_crs_id')
                        ->join('acd_turmas', 'ofc_id', '=', 'trm_ofc_id')
                        ->select('trm_id', 'trm_nome')
                        ->where('crs_id', '=', $cursoId)
                        ->get();

        return $entries;
    }

    public function findCursoByTurma($turmaId)
    {
        $turma = $this->find($turmaId);

        $curso = $turma->oferta->curso;

        return $curso;
    }
}
