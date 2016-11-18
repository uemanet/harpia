<?php

namespace Modulos\Integracao\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Integracao\Models\AmbienteVirtual;
use DB;

class AmbienteVirtualRepository extends BaseRepository
{
    public function __construct(AmbienteVirtual $ambientevirtual)
    {
        $this->model = $ambientevirtual;
    }
<<<<<<< HEAD

    public function verifyIfExistsAmbienteTurma($ambienteId, $turmaId)
    {
      $exists = DB::table('int_ambientes_turmas')
                  ->where('atr_amb_id', '=', $ambienteId)
                  ->where('atr_trm_id', '=', $turmaId)
                  ->first();

      if ($exists) {
          return true;
      }

      return false;

    }

=======
>>>>>>> 2f12d20f70c82c9b1b44dbd02e0b83adf50ef6b4
}
