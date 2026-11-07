<?php

namespace Modulos\Seguranca\Repositories;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modulos\Seguranca\Models\Modulo;
use Modulos\Core\Repository\BaseRepository;

class ModuloRepository extends BaseRepository
{
    public function __construct(Modulo $modulo)
    {
        parent::__construct($modulo);
    }

    public function getByUser($userId, $isMenu = false)
    {
        $modulos = DB::table('seg_modulos')
            ->join('seg_perfis', 'prf_mod_id', '=', 'mod_id')
            ->join('seg_perfis_usuarios', 'pru_prf_id', '=', 'prf_id')
            ->select('seg_modulos.*')
            ->where('pru_usr_id', '=', $userId)
            ->get();

        if ($isMenu) {
            return $modulos;
        }

        $cacheKey = 'PERMISSOES_' . $userId;

        $permissoesCache = $this->normalizePermissions(Cache::get($cacheKey, []));
        $modulosPorCache = $this->filterModulesByPermissions($modulos, $permissoesCache);

        // Self-heal stale cache by reconciling against current DB permissions.
        $permissoesDb = $this->loadPermissionsFromDatabase($userId);
        $modulosPorDb = $this->filterModulesByPermissions($modulos, $permissoesDb);

        if ($modulosPorDb->count() > $modulosPorCache->count()) {
            Cache::forever($cacheKey, $permissoesDb);

            return $modulosPorDb;
        }

        return $modulosPorCache;
    }

    private function normalizePermissions($permissoes)
    {
        if (is_array($permissoes)) {
            return $permissoes;
        }

        if ($permissoes instanceof \Illuminate\Support\Collection) {
            return $permissoes->toArray();
        }

        if (is_string($permissoes)) {
            $decoded = json_decode($permissoes, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }

        return [];
    }

    private function loadPermissionsFromDatabase($userId)
    {
        $permissions = DB::table('seg_permissoes')
            ->join('seg_permissoes_perfis', 'prm_id', '=', 'prp_prm_id')
            ->join('seg_perfis', 'prp_prf_id', '=', 'prf_id')
            ->join('seg_perfis_usuarios', 'pru_prf_id', '=', 'prf_id')
            ->where('pru_usr_id', '=', $userId)
            ->pluck('prm_rota')
            ->toArray();

        return $this->normalizePermissions($permissions);
    }

    private function filterModulesByPermissions($modulos, array $permissoes)
    {
        return $modulos->filter(function ($modulo) use ($permissoes) {
            return in_array($modulo->mod_slug . '.index.index', $permissoes, true);
        })->values();
    }
}
