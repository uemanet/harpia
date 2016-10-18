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

    public function verifyNameMatriz($moduloName, $idMatriz, $moduloId = null)
    {
        $result = $this->model->where('mdo_nome', $moduloName)->where('mdo_mtc_id', $idMatriz)->get();

        if (!$result->isEmpty()) {
            if (!is_null($moduloId)) {
                $result = $result->where('mdo_id', $moduloId);

                if (!$result->isEmpty()) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }
}
