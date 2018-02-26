<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\ModuloMatriz;
use DB;

class ModuloMatrizRepository extends BaseRepository
{
    public function __construct(ModuloMatriz $modulomatriz)
    {
        $this->model = $modulomatriz;
    }

    public function verifyNameMatriz($moduloName, $idMatriz, $moduloId = null)
    {
        $result = $this->model->where('mdo_nome', $moduloName)->where('mdo_mtc_id', $idMatriz)->get();

        if (!$result->isEmpty() && $moduloId) {
            $result = $result->where('mdo_id', $moduloId);

            return $result->isEmpty();
        }

        return !$result->isEmpty();
    }

    public function getAllModulosByMatriz($matrizId)
    {
        return $this->model->join('acd_matrizes_curriculares', function ($join) {
            $join->on('mdo_mtc_id', '=', 'mtc_id');
        })->where('mdo_mtc_id', '=', $matrizId)->get();
    }
}
