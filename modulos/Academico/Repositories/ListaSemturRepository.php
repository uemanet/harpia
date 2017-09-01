<?php

namespace Modulos\Academico\Repositories;

use Modulos\Academico\Models\ListaSemtur;
use Modulos\Core\Repository\BaseRepository;

class ListaSemturRepository extends BaseRepository
{
    public function __construct(ListaSemtur $model)
    {
        parent::__construct($model);
    }
}