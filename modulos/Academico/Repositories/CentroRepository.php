<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\Centro;

class CentroRepository extends BaseRepository
{
    public function __construct(Centro $centro)
    {
        $this->model = $centro;
    }

    public function delete($id)
    {
        $centro = $this->model->find($id);

        if ($centro->departamentos->count()) {
            return null;
        }

        return $this->model->destroy($id);
    }
}
