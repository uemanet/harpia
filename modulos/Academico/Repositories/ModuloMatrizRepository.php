<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\ModuloMatriz;

class ModuloMatrizRepository extends BaseRepository
{
    public function __construct(ModuloMatriz $modulomatriz)
    {
        $this->model = $modulomatriz;
    }

    public function paginateRequestByMatriz($matrizId, array $requestParameters = null)
    {
        $sort = array();
        if (!empty($requestParameters['field']) and !empty($requestParameters['sort'])) {
            $sort = [
                'field' => $requestParameters['field'],
                'sort' => $requestParameters['sort']
            ];
            return $this->model->where('mdo_mtc_id', '=', $matrizId)
                ->orderBy($sort['field'], $sort['sort'])
                ->paginate(15);
        }
        return $this->model->where('mdo_mtc_id', '=', $matrizId)->paginate(15);
    }
}
