<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\Disciplina;

class DisciplinaRepository extends BaseRepository
{
    public function __construct(Disciplina $disciplina)
    {
        $this->model = $disciplina;
    }

    /**
     * Cas
     * @param array $data
     * @param null $id
     * @return bool
     */
    public function validacao(array $data, $id = null)
    {
        $result = $this->model
            ->where('dis_nvc_id', $data['dis_nvc_id'])
            ->where('dis_creditos', $data['dis_creditos'])
            ->where('dis_nome', $data['dis_nome'])
            ->where('dis_carga_horaria', $data['dis_carga_horaria'])->get();

        if ($result->isEmpty()) {
            return true;
        }

        if (!is_null($id)) {
            $result = $result->where('dis_id', $id);

            return !$result->isEmpty();
        }

        return false;
    }
}
