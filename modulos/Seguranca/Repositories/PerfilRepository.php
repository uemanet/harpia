<?php

namespace App\Modulos\Seguranca\Repositories;

use Bosnadev\Repositories\Eloquent\Repository;
use DB;

class PerfilRepository extends Repository
{
    /**
     * Specify Models class name.
     *
     * @return string
     */
    public function model(){
       return 'App\Models\Security\Perfil';
    }

     public function getAllPerfisWithModulos()
    {
        $sql = 'SELECT prf_id,prf_nome,prf_descricao,mod_nome
                FROM seg_perfis
                    INNER JOIN seg_modulos ON mod_id = prf_mod_id
                ORDER BY mod_nome';

        return DB::select($sql);
    }

    public function getPerfilWithModulo($perfilId)
    {
        $sql = 'SELECT prf_id,prf_nome,prf_descricao,prf_mod_id,mod_nome
                FROM seg_perfis
                	INNER JOIN seg_modulos m ON mod_id = prf_mod_id
                WHERE prf_id = :perfilid';

        return DB::selectOne($sql, ['perfilid' => $perfilId]);
    }

    public function getPerfilWithModuloByUsuarioId($usuarioId)
    {
    	$sql = 'SELECT prf_id,prf_nome,prf_descricao,prf_mod_id,mod_nome
                FROM seg_perfis
                	INNER JOIN seg_perfis_usuarios ON prf_id = pru_prf_id
                	INNER JOIN seg_modulos ON mod_id = prf_mod_id
                WHERE pru_usr_id = :usuarioid';

        $result = DB::select($sql, ['usuarioid' => $usuarioId]);

        return $result;
    }

    public function getModulosByUsuarioId($usuarioId)
    {
    	$sql = 'SELECT prf_mod_id as mod_id FROM seg_perfis INNER JOIN seg_perfis_usuarios ON prf_id = pru_prf_id WHERE pru_usr_id = :usuarioid';
        $result = DB::select($sql, ['usuarioid' => $usuarioId]);

        return $result;
    }

    public function getPerfisNaoAtribuidos($moduloId)
    {
        return DB::table('seg_perfis')->select('prf_id', 'prf_nome')->where('prf_mod_id', $moduloId)->get();
    }

    public function getTreeOfPermissoesByPefilAndModulo($perfilId, $moduloId)
    {
        $sql = 'SELECT
                  rcs_id, rcs_nome, rcs_nome, prm_id,prm_nome, (CASE WHEN bol=1 THEN 1 ELSE 0 END) AS habilitado
                FROM (
                    SELECT rcs_id, rcs_nome, prm_id, prm_nome FROM seg_permissoes
                    LEFT JOIN seg_recursos ON rcs_id = prm_rcs_id
                    WHERE rcs_mod_id = :moduloid
                ) todas
                LEFT JOIN (
                    SELECT prm_id as temp, 1 as bol FROM seg_permissoes
                    LEFT JOIN seg_recursos ON rcs_id = prm_rcs_id
                    LEFT JOIN seg_perfis_permissoes ON prp_prm_id = prm_id
                    WHERE prp_prf_id = :perfilid
                ) menos ON todas.prm_id = menos.temp';

        $permissoes = DB::select($sql, ['moduloid' => $moduloId, 'perfilid' => $perfilId]);

        $retorno = [];
        if (count($permissoes)) {
            foreach ($permissoes as $key => $perm) {
                if (isset($retorno[$perm->rcs_id])) {
                    $retorno[$perm->rcs_id]['permissoes'][$perm->prm_id] = array('prm_id' => $perm->prm_id, 'prm_nome' => $perm->prm_nome, 'habilitado' => $perm->habilitado);
                } else {
                    $retorno[$perm->rcs_id]['rcs_id'] = $perm->rcs_id;
                    $retorno[$perm->rcs_id]['rcs_nome'] = $perm->rcs_nome;
                    $retorno[$perm->rcs_id]['permissoes'][$perm->prm_id] = array('prm_id' => $perm->prm_id, 'prm_nome' => $perm->prm_nome, 'habilitado' => $perm->habilitado);
                }
            }
        }

        return $retorno;
    }

    // /**
    //  * Sincroniza as permissões do perfil na base.
    //  *
    //  * @param $perfilId
    //  * @param array $permissoes
    //  */
    public function sincronizarPermissoes($perfilId, array $permissoes)
    {
        return $this->model->find($perfilId)->permissoes()->sync($permissoes);
    }

    public function getPerfis($moduloId)
    {
        return DB::table('seg_perfis')->select('prf_id', 'prf_nome')->where('prf_mod_id', $moduloId)->get();
    }
}
