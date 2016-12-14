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

        if ($verificar->isEmpty()) {
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

    public function getAllTurmasWithTcc($id)
    {
        $result = $this->model
            ->where('mdc_tipo_disciplina', '=', 'tcc')
            ->join('acd_disciplinas', 'mdc_dis_id', 'dis_id')
            ->join('acd_niveis_cursos', 'acd_disciplinas.dis_nvc_id', 'nvc_id')
            ->where('mdc_mdo_id', '=', $id)->get();

        return $result;
    }

    public function verifyDisciplinaAdicionada($data)
    {
        $result = $this->model
              ->where('mdc_dis_id', '=', $data['dis_id']);
    }

    public function paginate($sort = null, $search = null)
    {
        $result = $this->model
            ->join('acd_disciplinas', function ($join) {
            $join->on('mdc_dis_id', '=', 'dis_id');
        })
            ->join('acd_ofertas_disciplinas', function ($join) {
            $join->on('ofd_mdc_id', '=', 'mdc_id');
        })
            ->join('acd_matriculas_ofertas_disciplinas', function ($join) {
            $join->on('mof_ofd_id', '=', 'ofd_id');
        })
            ->join('acd_matriculas', function ($join) {
            $join->on('mof_mat_id', '=', 'mat_id');
        })
            ->join('acd_turmas', function ($join) {
            $join->on('mat_trm_id', '=', 'trm_id');
        })
            ->join('acd_ofertas_cursos', function ($join) {
            $join->on('trm_ofc_id', '=', 'ofc_id');
        })
            ->where('mdc_tipo_disciplina', '=', 'tcc')
            ->groupby('trm_id')->distinct();

        if (!empty($search)) {
            foreach ($search as $value) {
                if ($value['field'] == 'pes_cpf') {
                    $result = $result->where('doc_conteudo', '=', $value['term']);
                    continue;
                }

                switch ($value['type']) {
                    case 'like':
                        $result = $result->where($value['field'], $value['type'], "%{$value['term']}%");
                        break;
                    default:
                        $result = $result->where($value['field'], $value['type'], $value['term']);
                }
            }
        }

        if (!empty($sort)) {
            $result = $result->orderBy($sort['field'], $sort['sort']);
        }

        $result = $result->paginate(15);

        return $result;
    }
}
