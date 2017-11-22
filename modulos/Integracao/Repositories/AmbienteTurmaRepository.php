<?php
declare(strict_types=1);

namespace Modulos\Integracao\Repositories;

use DB;
use Modulos\Core\Repository\BaseRepository;
use Modulos\Integracao\Models\AmbienteTurma;

class AmbienteTurmaRepository extends BaseRepository
{
    public function __construct(AmbienteTurma $ambienteturma)
    {
        $this->model = $ambienteturma;
    }
}
