<?php

namespace Modulos\Seguranca\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Seguranca\Models\Perfil;
use DB;

class PerfilRepository extends BaseRepository
{
    public function __construct(Perfil $perfil)
    {
        $this->model = $perfil;
    }

    public function getPerfilWithModulo($perfilId)
    {
        $sql = 'SELECT prf_id,prf_nome,prf_descricao,prf_mod_id,mod_nome
                FROM seg_perfis
                    INNER JOIN seg_modulos m ON mod_id = prf_mod_id
                WHERE prf_id = :perfilid';

        return DB::selectOne($sql, ['perfilid' => $perfilId]);
    }

    public function getTreeOfPermissoesByPefilAndModulo($perfilId, $moduloId)
    {
        $sql = 'SELECT
                  rcs_id, rcs_nome, rcs_nome, prm_id,prm_nome, (CASE WHEN bol=1 THEN 1 ELSE 0 END) AS habilitado
                FROM (
                    SELECT rcs_id, rcs_nome, prm_id, prm_nome 
                    FROM seg_permissoes
                        LEFT JOIN seg_recursos ON rcs_id = prm_rcs_id
                        LEFT JOIN seg_categorias_recursos ON rcs_ctr_id = ctr_id
                    WHERE ctr_mod_id = :moduloid
                ) todas
                LEFT JOIN (
                    SELECT prm_id as temp, 1 as bol 
                    FROM seg_permissoes
                        LEFT JOIN seg_recursos ON rcs_id = prm_rcs_id
                        LEFT JOIN seg_categorias_recursos ON rcs_ctr_id = ctr_id
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

    public function sincronizarPermissoes($perfilId, array $permissoes)
    {
        return $this->model->find($perfilId)->permissoes()->sync($permissoes);
    }

    public function getAllByModulo($moduloId)
    {
        return $result = $this->model->where('prf_mod_id', '=', $moduloId)->get();
    }

    public function getModulosWithoutPerfis($usuarioId)
    {
        $sql = 'SELECT mod_id, mod_nome 
                FROM (SELECT mod_id, mod_nome, prf_id FROM seg_modulos LEFT JOIN seg_perfis ON mod_id = prf_mod_id) modulos
                LEFT JOIN (SELECT * FROM seg_perfis_usuarios WHERE pru_usr_id = :usuario) perfis
                ON modulos.prf_id = perfis.pru_prf_id
                WHERE pru_prf_id IS NULL';

        $modulos = DB::select($sql, ['usuario' => $usuarioId]);

        $retorno = [];

        if ($modulos) {
            foreach ($modulos as $modulo) {
                $retorno[$modulo->mod_id] = $modulo->mod_nome;
            }
        }

        return $retorno;
    }

    public function verifyExistsPerfilModulo($moduloId, $usuarioId)
    {
        $result = $this->model->join('seg_modulos', function ($join) {
            $join->on('prf_mod_id', '=', 'mod_id');
        })->join('seg_perfis_usuarios', function ($join) use ($usuarioId) {
            $join->on('prf_id', '=', 'pru_prf_id')->where('pru_usr_id', '=', $usuarioId);
        })->where('mod_id', $moduloId)->first();

        if ($result) {
            return true;
        }

        return false;
    }
}
