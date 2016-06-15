<?php

namespace Modulos\Geral\Models;

use Modulos\Core\Model\BaseModel;

class Colaborador extends BaseModel
{

    protected $table = 'gra_colaborador';

    protected $primaryKey = 'col_pres_id';

    protected $fillable = [
        'col_fun_id',
        'col_set_id',
        'col_matricula',
        'col_data_admissao'
    ];

    public function setor()
    {
        return $this->belongsTo('Modulos\Geral\Models\Setor', 'col_set_id', 'set_id');
    }

    public function funcao()
    {
        return $this->belongsTo('Modulos\Geral\Models\Funcao', 'col_fun_id', 'fun_id');
    }
}