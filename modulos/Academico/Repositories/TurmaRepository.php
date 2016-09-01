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

    public function findAllByOfertaCurso($ofertaCursoId)
    {
        $entries = DB::table('acd_turmas')
                        ->select('trm_id', 'trm_nome')
                        ->where('trm_ofc_id', '=', $ofertaCursoId)
                        ->get();

        return $entries;
    }
}
