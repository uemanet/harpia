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

    /**
     * Retorna todos os polos de acordo com a Oferta de Curso
     * @param  int $ofertaCursoId
     * @return array
     */
    public function findAllByOfertaCurso($ofertaCursoId)
    {
        $entries = DB::table('acd_polos_ofertas_cursos')
                        ->join('acd_polos', 'poc_pol_id', 'pol_id')
                        ->where('poc_ofc_id', $ofertaCursoId)
                        ->get();

        return $entries;
    }
}
