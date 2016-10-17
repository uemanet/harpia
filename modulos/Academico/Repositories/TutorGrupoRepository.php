<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\TutorGrupo;

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

    public function verifyTutorExists()
    {
        $result_presencial = $this->model
            ->where('ttg_tipo_tutoria', 'presencial')
            ->get();

        $result_distancia = $this->model
            ->where('ttg_tipo_tutoria', 'distancia')
            ->get();

        if ($result_distancia->isEmpty() || $result_presencial->isEmpty()) {
            return true;
        }
    }

    public function verifyTutorPresencial($tipoTutoria)
    {
        $result_presencial = $this->model
          ->where('ttg_tipo_tutoria', 'presencial')
          ->get();

        if (!($result_presencial->isEmpty()) && $tipoTutoria === "presencial" ) {
            return true;
        }
    }

    public function verifyTutorDistancia($tipoTutoria)
    {
        $result_distancia = $this->model
          ->where('ttg_tipo_tutoria', 'distancia')
          ->get();

        if (!($result_distancia->isEmpty()) && $tipoTutoria === "distancia" ) {
            return true;
        }
    }

}
