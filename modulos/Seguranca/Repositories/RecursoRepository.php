<?php

namespace App\Modulos\Seguranca\Repositories;

use Bosnadev\Repositories\Eloquent\Repository;
use DB;

class RecursoRepository extends Repository
{
    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
       return 'App\Models\Security\Recurso';
    }

    public function getItemSelect()
    {
    	$recursos = DB::table('seg_recursos')
            ->join('seg_modulos', 'rcs_mod_id', '=', 'mod_id')
            ->select('mod_nome', 'rcs_id', 'rcs_nome','rcs_descricao')
            ->orderBy('mod_nome','asc')
            ->orderBy('rcs_nome','asc')
            ->get();

        $result = [];

        foreach ($recursos as $key => $recurso) {
            $result[mb_strtoupper($recurso->mod_nome)][$recurso->rcs_id] = mb_strtoupper($recurso->rcs_descricao);
        }

        return $result;
    }
}
