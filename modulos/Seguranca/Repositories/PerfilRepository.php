<?php

namespace Modulos\Seguranca\Repositories;

use DB;
use Modulos\Seguranca\Models\Perfil;
use Modulos\Core\Repository\BaseRepository;

class PerfilRepository extends BaseRepository
{
    public function __construct(Perfil $perfil)
    {
        parent::__construct($perfil);
    }

    public function getPerfilModulo($perfil)
    {
        $permissoes = DB::table('seg_permissoes')
            ->where('prm_rota', 'like', $perfil->modulo->mod_slug . '%')->get();

        $arrayRecursos = [];
        $arrayRecursosPermissoes = [];

        foreach ($permissoes as $key => $permissao) {
            $habilitado = DB::table('seg_permissoes_perfis')
                ->where('prp_prf_id', '=', $perfil->prf_id)
                ->where('prp_prm_id', '=', $permissao->prm_id)
                ->get();

            if ($habilitado->isEmpty()) {
                $habilitado = 0;
            } else {
                $habilitado = 1;
            }

            $separa = explode('.', $permissao->prm_rota);
            $conta = count($separa);
            $arrayPermissoes = [
                'prm_id' => $permissao->prm_id,
                'prm_nome' => $separa[$conta - 1],
                'habilitado' => $habilitado
            ];

            $arrayRecursosPermissoes[$key]['rcs_nome'] = $separa[$conta - 2];
            $arrayRecursosPermissoes[$key]['permissao'] = $arrayPermissoes;

            if (!in_array($separa[$conta - 2], $arrayRecursos)) {
                $arrayRecursos[] = $separa[$conta - 2];
            }
        }

        $retornoperfis = [];

        foreach ($arrayRecursos as $key => $arrayRecurso) {
            $retornoperfis[$key]['rcs_id'] = 0;
            $retornoperfis[$key]['rcs_nome'] = $arrayRecurso;
            $retornoperfis[$key]['permissoes'] = 'arraydepermissoes';
        }

        $arraypermissoes = [];
        foreach ($retornoperfis as $keyA => $novo) {
            foreach ($arrayRecursosPermissoes as $keyB => $arrayRecurso) {
                $stringA = $arrayRecurso['rcs_nome'];
                $stringB = $novo['rcs_nome'];

                if ($stringA == $stringB) {
                    $arraypermissoes[] = $arrayRecurso['permissao'];
                }
            }
            $retornoperfis[$keyA]['permissoes'] = $arraypermissoes;
            $arraypermissoes = [];
        }

        return $retornoperfis;
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
        $subquery = DB::table('seg_modulos')
            ->leftJoin('seg_perfis', 'mod_id', '=', 'prf_mod_id')
            ->select('mod_id', 'mod_nome', 'prf_id')
            ->groupBy('mod_id');

        $result = DB::table(DB::raw("({$subquery->toSql()}) as modulos"))
            ->leftJoin('seg_perfis_usuarios', function ($join) use ($usuarioId) {
                $join->on('modulos.prf_id', '=', 'pru_prf_id')->where('pru_usr_id', '=', $usuarioId);
            })
            ->whereNull('pru_prf_id')
            ->get();

        $retorno = [];

        if ($result) {
            foreach ($result as $modulo) {
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
