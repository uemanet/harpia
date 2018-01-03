<?php

namespace Modulos\Academico\Repositories;

use Modulos\Academico\Models\Livro;
use Modulos\Core\Repository\BaseRepository;

class LivroRepository extends BaseRepository
{
    public function __construct(Livro $livro)
    {
        $this->model = $livro;
    }

    public function findBy(array $options = null)
    {
        $query = $this->model;

        if (!empty($options)) {
            foreach ($options as $key => $value) {
                $query = $query->where($key, '=', $value);
            }

            return $query->get();
        }

        return $query->all();
    }
}
