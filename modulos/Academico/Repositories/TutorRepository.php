<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\Tutor;
use DB;

class TutorRepository extends BaseRepository
{
    public function __construct(Tutor $tutor)
    {
        $this->model = $tutor;
    }

    public function paginate($sort = null, $search = null)
    {
        $result = $this->model->join('gra_pessoas', function ($join) {
            $join->on('tut_pes_id', '=', 'pes_id');
        })->leftJoin('gra_documentos', function ($join) {
            $join->on('pes_id', '=', 'doc_pes_id')->where('doc_tpd_id', '=', 2, 'and', true);
        });

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



    public function listsTutorPessoa($idGrupo)
    {
        $tutoresvinculados = DB::table('acd_tutores')
         ->join('acd_tutores_grupos', 'ttg_tut_id', '=', 'tut_id')
         ->join('acd_grupos', 'ttg_grp_id', '=', 'grp_id')
         ->where('grp_id', '=', $idGrupo)
         ->where('ttg_data_fim', null)
         ->get();
        $tutoresvinculadosId = [];

        foreach ($tutoresvinculados as $key => $value) {
            $tutoresvinculadosId[] = $value->tut_id;
        }

        $tutores = DB::table('acd_tutores')
           ->join('gra_pessoas', 'tut_pes_id', '=', 'pes_id')
           ->whereNotIn('tut_id', $tutoresvinculadosId)
           ->pluck('pes_nome', 'tut_id');
        return $tutores;
    }
}
