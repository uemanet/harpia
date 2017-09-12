<?php

namespace Modulos\Academico\Models;

use Modulos\Core\Model\BaseModel;

class ListaSemtur extends BaseModel
{
    protected $table = 'acd_listas_semtur';

    protected $primaryKey = 'lst_id';

    protected $fillable = ['lst_nome', 'lst_descricao', 'lst_data_bloqueio'];

    protected $searchable = [
        'lst_id' => '=',
        'lst_nome' => 'like',
        'pes_nome' => 'like',
        'mat_trm_id' => '=',
        'mat_pol_id' => '='
    ];

    public function matriculas()
    {
        return $this->belongsToMany('Modulos\Academico\Models\Matricula', 'acd_matriculas_listas_semtur', 'mls_lst_id', 'mls_mat_id');
    }

    public function setLstDataBloqueioAttribute($value)
    {
        $this->attributes['lst_data_bloqueio'] = \Carbon\Carbon::createFromFormat('d/m/Y', $value)->toDateString();
    }

    public function getLstDataBloqueioAttribute($value)
    {
        if ($value) {
            return \Carbon\Carbon::createFromFormat('Y-m-d', $value)->formatLocalized('%d/%m/%Y');
        }

        return null;
    }
}