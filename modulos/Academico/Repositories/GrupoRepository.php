<?php
namespace Modulos\Academico\Repositories;

use Modulos\Academico\Models\Grupo;
use Modulos\Core\Repository\BaseRepository;

class GrupoRepository extends BaseRepository
{
    public function __construct(Grupo $grupo)
    {
        $this->model = $grupo;
    }

    /**
     * PaginateRequest
     * @param array|null $requestParameters
     * @return mixed
     */
    public function paginateRequestByTurma($turmaid, array $requestParameters = null)
    {
        $sort = array();
        if (!empty($requestParameters['field']) and !empty($requestParameters['sort'])) {
            $sort = [
                'field' => $requestParameters['field'],
                'sort' => $requestParameters['sort']
            ];
            return $this->model->where('grp_trm_id', '=', $turmaid)
                ->orderBy($sort['field'], $sort['sort'])
                ->paginate(15);
        }
        return $this->model->where('grp_trm_id', '=', $turmaid)->paginate(15);
    }
}
