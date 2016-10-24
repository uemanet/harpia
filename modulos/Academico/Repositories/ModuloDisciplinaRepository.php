<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\ModuloDisciplina;
use DB;

class ModuloDisciplinaRepository extends BaseRepository
{
    public function __construct(ModuloDisciplina $modulodisciplina)
    {
        $this->model = $modulodisciplina;
    }

    public function verifyDisciplinaModulo($idDisciplina)
    {



      $disciplina = DB::table('acd_disciplinas')
          ->where('dis_id', '=', $idDisciplina)->pluck('dis_nome', 'dis_id');

      dd($disciplina[$idDisciplina]);

      $disciplina = DB::table('acd_disciplinas')
          ->join('acd_modulos_disciplinas', 'pes_id', '=', 'acd_professores.prf_pes_id');
          ->where('dis_id', '=', $idDisciplina)->pluck('dis_nome', 'dis_id');

    }

}
