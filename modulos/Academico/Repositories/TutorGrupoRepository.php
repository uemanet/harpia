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
        $returnArray = [];

        $returnArray['distancia'] = 'Tutor a Distancia';
        $returnArray['presencial'] = 'Tutor Presencial';
        $returnArray['orientador'] = 'Orientador';

        return $returnArray;
    }
}
