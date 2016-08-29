<?php

namespace Modulos\Academico\Repositories;

use Illuminate\Support\Facades\DB;
use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\Polo;

class PoloRepository extends BaseRepository
{
    public function __construct(Polo $polo)
    {
        $this->model = $polo;
    }

    public function findAllByCurso($idCurso)
    {
        $entries = DB::table('acd_cursos')
                        ->join('acd_ofertas_cursos', 'crs_id', '=', 'ofc_crs_id')
                        ->join('acd_polos_ofertas_cursos', 'ofc_id', '=', 'poc_ofc_id')
                        ->join('acd_polos', 'poc_pol_id', '=', 'pol_id')
                        ->select('pol_id', 'pol_nome')
                        ->where('crs_id', '=', $idCurso)
                        ->get();

        return $entries;
    }
}
