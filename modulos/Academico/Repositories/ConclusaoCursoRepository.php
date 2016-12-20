<?php

namespace Modulos\Academico\Repositories;

use Illuminate\Http\Request;
use Modulos\Core\Repository\BaseRepository;

class ConclusaoCursoRepository extends BaseRepository
{
    
    public function __construct(\Modulos\Core\Model\BaseModel $model)
    {
        parent::__construct($model);
    }

    public function getAlunosAptosOrNot($ofertaCursoId, $turmaId, $poloId)
    {
        // busca as informacoes da oferta de curso
    }
}