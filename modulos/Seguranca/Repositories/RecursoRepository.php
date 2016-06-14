<?php

namespace Modulos\Seguranca\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Seguranca\Models\Recurso;
use Illuminate\Support\Facades\DB;

class RecursoRepository extends BaseRepository
{
    public function __construct(Recurso $recurso)
    {
        $this->model = $recurso;
    }

    public function findAllByModulo($moduloId)
    {
        $recursos = DB::table('seg_recursos')
            ->join('seg_categorias_recursos', 'ctr_id', '=', 'rcs_ctr_id')
            ->join('seg_modulos', 'mod_id', '=', 'ctr_mod_id')
            ->select('rcs_id', 'rcs_nome')
            ->where('mod_id', $moduloId)
            ->get();

        return $recursos;
    }

    /**
     * Busca todos os recursos de acordo com o modulo informado e retorna como lists para popular um field select
     *
     * @param $moduloid
     *
     * @return mixed
     */
    public function listsAllByModulo($moduloId)
    {
        $recursos = DB::table('seg_recursos')
            ->join('seg_categorias_recursos', 'ctr_id', '=', 'rcs_ctr_id')
            ->join('seg_modulos', 'mod_id', '=', 'ctr_mod_id')
            ->where('mod_id', $moduloId)
            ->pluck('rcs_nome', 'rcs_id');

        return $recursos;
    }
}
