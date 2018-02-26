<?php

namespace Modulos\Integracao\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Integracao\Models\AmbienteTurma;
use DB;

class AmbienteTurmaRepository extends BaseRepository
{
    public function __construct(AmbienteTurma $ambienteturma)
    {
        parent::__construct($ambienteturma);
    }

    public function verificaPendenciasTurma($turmaId)
    {
        $result = DB::table('acd_ofertas_disciplinas')
                    ->where('ofd_trm_id', $turmaId);

        if ($result->count()) {
            return true;
        }

        $result = DB::table('acd_matriculas')
                    ->where('mat_trm_id', $turmaId);

        if ($result->count()) {
            return true;
        }

        $result = DB::table('acd_grupos')
                    ->where('grp_trm_id', $turmaId);

        if ($result->count()) {
            return true;
        }

        return false;
    }
}
