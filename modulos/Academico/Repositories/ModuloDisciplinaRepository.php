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

    public function verifyDisciplinaModulo($idDisciplina, $idModulo)
    {



      $disciplina = DB::table('acd_disciplinas')
          ->where('dis_id', '=', $idDisciplina)->pluck('dis_nome', 'dis_id');


      $verificar = DB::table('acd_disciplinas')
          ->join('acd_modulos_disciplinas', 'dis_id', '=', 'acd_modulos_disciplinas.mdc_dis_id')
          ->join('acd_modulos_matrizes', 'acd_modulos_disciplinas.mdc_mdo_id', '=', 'acd_modulos_matrizes.mdo_id')
          ->join('acd_matrizes_curriculares', 'acd_modulos_matrizes.mdo_mtc_id', '=', 'acd_matrizes_curriculares.mtc_id')
          ->where('acd_modulos_disciplinas.mdc_mdo_id', '=', $idModulo)
          ->where('dis_nome', '=', $disciplina[$idDisciplina])->get();

          if ($verificar->isEmpty()){
            return false;
          };

          return true;

    }

    public function getAllDisciplinasByModulo($id)
    {
        $result = $this->model->join('acd_disciplinas', 'mdc_dis_id', 'dis_id')
            ->join('acd_niveis_cursos', 'acd_disciplinas.dis_nvc_id', 'nvc_id')
            ->where('mdc_mdo_id', '=', $id)->get();

        return $result;

    }

    public function verifyDisciplinaAdicionada($data)
    {

      $result = $this->model
              ->where('mdc_dis_id', '=', $data['dis_id']);

    }

}
