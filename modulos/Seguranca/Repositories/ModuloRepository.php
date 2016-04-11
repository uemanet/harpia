<?php

namespace App\Modulos\Seguranca\Repositories;

use Bosnadev\Repositories\Eloquent\Repository;
use DB;

class ModuloRepository extends Repository
{
    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
       return 'App\Models\Security\Modulo';
    }

    public function getModulosNaoVinculados($modulosVinculados)
    {
        $modulosNaoVinculados = DB::table('seg_modulos')->select('mod_id', 'mod_nome')->whereNotIn('mod_id', $modulosVinculados)->get();
        return $modulosNaoVinculados;
    }

    public function getModulosUsuario($usrId)
    {
 	    $res = DB::table('seg_modulos')
            ->join('seg_perfis', 'prf_mod_id', '=', 'mod_id')
            ->join('seg_perfis_usuarios', 'pru_prf_id', '=', 'prf_id')
            ->select(array('seg_modulos.*'))
            ->where('mod_ativo', '=', 1)
            ->where('pru_usr_id', '=', $usrId)
            ->get();

        if (empty($res)) {
            return [];
        }

        return $res;
    }
}
