<?php

namespace Modulos\Seguranca\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Seguranca\Models\Permissao;
use Illuminate\Support\Facades\DB;

class PermissaoRepository extends BaseRepository
{
    public function __construct(Permissao $permissao)
    {
        $this->model = $permissao;
    }

    public function findModulo($permissaoId)
    {
        $modulo = DB::table('seg_permissoes')
            ->join('seg_recursos', 'rcs_id', '=', 'prm_rcs_id')
            ->join('seg_categorias_recursos', 'ctr_id', '=', 'rcs_ctr_id')
            ->join('seg_modulos', 'mod_id', '=', 'ctr_mod_id')
            ->select('seg_modulos.*')
            ->where('prm_id', $permissaoId)
            ->first();

        return $modulo;
    }
}
