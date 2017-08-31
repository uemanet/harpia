<?php

namespace Modulos\Seguranca\Models;

use Modulos\Core\Model\BaseModel;
use DB;

class Permissao extends BaseModel
{
    protected $table = 'seg_permissoes';

    protected $primaryKey = 'prm_id';

    protected $fillable = ['prm_nome', 'prm_rota', 'prm_descricao'];

    protected $searchable = [
        'prm_nome' => 'like',
        'prm_rota' => 'like'
    ];

    public function perfis()
    {
        return $this->belongsToMany('Modulos\Seguranca\Models\Perfil', 'seg_permissoes_perfis', 'prp_prm_id', 'prp_prf_id');
    }

    public function modulo()
    {
        $nomeModulo = explode('.', $this->prm_rota)[0];

        return DB::table('seg_modulos')->where('mod_slug', $nomeModulo)->first();
    }

    public function slugModulo()
    {
        $slugModulo = explode('.', $this->prm_rota)[0];
        return $slugModulo;
    }
}
