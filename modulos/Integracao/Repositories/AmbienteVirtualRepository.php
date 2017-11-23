<?php
declare(strict_types=1);

namespace Modulos\Integracao\Repositories;

use DB;
use Modulos\Core\Repository\BaseRepository;
use Modulos\Integracao\Models\AmbienteVirtual;
use Modulos\Academico\Repositories\TurmaRepository;

class AmbienteVirtualRepository extends BaseRepository
{
    protected $turmaRepository;

    public function __construct(AmbienteVirtual $ambientevirtual, TurmaRepository $turmaRepository)
    {
        parent::__construct($ambientevirtual);
        $this->turmaRepository = $turmaRepository;
    }

    public function getAmbienteByTurma(int $turmaId)
    {
        $turma = $this->turmaRepository->find($turmaId);
        return $turma->ambientes->first();
    }
}
