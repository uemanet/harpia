<?php

namespace Modulos\RH\Models;

use Modulos\Core\Model\BaseModel;

class GestorSetor extends BaseModel
{
    protected $table = 'reh_gestores_setor';

    protected $primaryKey = 'gst_id';

    protected $fillable = [
        'gst_col_id',
        'gst_set_id',
        'gst_ativo',
    ];

    protected $searchable = [
        'gst_set_id' => '=',
        'gst_ativo' => '=',
    ];

    public function colaborador()
    {
        return $this->belongsTo('Modulos\RH\Models\Colaborador', 'gst_col_id', 'col_id');
    }

    public function setor()
    {
        return $this->belongsTo('Modulos\RH\Models\Setor', 'gst_set_id', 'set_id');
    }
}
