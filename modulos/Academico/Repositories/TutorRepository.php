<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\Tutor;
use DB;
use Auth;

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

        $user = Auth::user();
        if (!$user->isAdmin()){
            $result = $result->where('pes_itt_id', $user->pessoa->pes_itt_id);
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
           ->orderBy('pes_nome');

        $user = Auth::user();
        if (!$user->isAdmin()){
            $tutores = $tutores->where('pes_itt_id', $user->pessoa->pes_itt_id);
        }

        return $tutores->pluck('pes_nome', 'tut_id');
    }

    public function findAllByGrupo($GrupoId)
    {
        $entries = DB::table('acd_tutores')
            ->join('gra_pessoas', 'tut_pes_id', '=', 'pes_id')
            ->join('acd_tutores_grupos', 'ttg_tut_id', '=', 'tut_id')
            ->select('tut_id', 'pes_nome')
            ->where('ttg_grp_id', '=', $GrupoId)
            ->get();

        return $entries;
    }

    public function FindallbyTurmaTipoTutoria($idTurma, $tipoTutoria)
    {
        $entries = DB::table('acd_tutores_grupos')
            ->join('acd_grupos', 'ttg_grp_id', '=', 'grp_id')
            ->join('acd_turmas', 'grp_trm_id', '=', 'trm_id')
            ->join('acd_tutores', 'ttg_tut_id', '=', 'tut_id')
            ->join('gra_pessoas', 'tut_pes_id', '=', 'pes_id')
            ->select('pes_id', 'pes_nome')
            ->where('trm_id', '=', $idTurma)
            ->where('ttg_tipo_tutoria', '=', $tipoTutoria)
            ->distinct('ttg_tut_id')
            ->orderBy('pes_nome')
            ->get();

        return $entries;
    }
}
