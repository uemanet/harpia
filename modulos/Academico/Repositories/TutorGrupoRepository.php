<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\TutorGrupo;
use DB;

class TutorGrupoRepository extends BaseRepository
{
    public function __construct(TutorGrupo $tutorgrupo)
    {
        $this->model = $tutorgrupo;
    }

    public function paginateRequestByGrupo($grupoid, array $requestParameters = null)
    {
        $sort = array();
        if (!empty($requestParameters['field']) and !empty($requestParameters['sort'])) {
            $sort = [
                'field' => $requestParameters['field'],
                'sort' => $requestParameters['sort']
            ];
            return $this->model->where('ttg_grp_id', '=', $grupoid)->where('ttg_data_fim', '=', null)
                ->orderBy($sort['field'], $sort['sort'])
                ->paginate(15);
        }
        return $this->model->where('ttg_grp_id', '=', $grupoid)->where('ttg_data_fim', '=', null)->paginate(15);
    }


    public function getTiposTutoria($grupoId)
    {
        $distancia = $this->model
            ->where('ttg_tipo_tutoria', 'distancia')
            ->where('ttg_data_fim', null)
            ->where('ttg_grp_id', $grupoId)
            ->count();
        $presencial = $this->model
            ->where('ttg_tipo_tutoria', 'presencial')
            ->where('ttg_grp_id', $grupoId)
            ->where('ttg_data_fim', null)->count();
        $returnArray = [];

        if ($distancia<2) {
            $returnArray['distancia'] = 'A Distancia';
        }

        if ($presencial<2) {
            $returnArray['presencial'] = 'Presencial';
        }

        return $returnArray;
    }
}
