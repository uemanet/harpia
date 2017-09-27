<?php

namespace Modulos\Academico\Repositories;

use Modulos\Academico\Models\Matricula;
use Modulos\Core\Repository\BaseRepository;

class ResultadosFinaisRepository extends BaseRepository
{
    public function __construct(Matricula $matricula)
    {
        parent::__construct($matricula);
    }

    public function create(array $data)
    {
        throw new \Exception("Cannot create entry for " . json_encode($data));
    }

    public function update(array $data, $id, $attribute = null)
    {
        throw new \Exception("Cannot update entry for " . json_encode([$data, $id, $attribute]));
    }

    public function delete($id)
    {
        throw new \Exception("Cannot delete entry for " . $id);
    }
}
