<?php

namespace App\Modulos\Seguranca\Repositories;

use Bosnadev\Repositories\Eloquent\Repository;
use DB;
use Carbon\Carbon;

class PerfilUsuarioRepository extends Repository
{
    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
       return 'App\Models\Security\PerfilUsuario';
    }

    public function atribuirPerfil($usuarioId, $perfilId)
    {
        $sql = "INSERT INTO seg_perfis_usuarios (pru_usr_id,pru_prf_id, created_at, updated_at)
				VALUES (:usuarioid, :perfilid, '".Carbon::now()."', '".Carbon::now()."')";

        return DB::insert($sql, ['usuarioid' => $usuarioId, 'perfilid' => $perfilId]);
    }

    public function desvincularPerfil($usuarioId, $perfilId)
    {
        $sql = 'DELETE FROM seg_perfis_usuarios WHERE pru_usr_id = :usuarioid AND pru_prf_id = :perfilid';
        return DB::delete($sql, ['usuarioid' => $usuarioId, 'perfilid' => $perfilId]);
    }
}
