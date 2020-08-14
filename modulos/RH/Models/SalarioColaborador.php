<?php

namespace Modulos\RH\Models;

use Carbon\Carbon;
use Modulos\Core\Model\BaseModel;

class SalarioColaborador extends BaseModel
{
    protected $table = 'reh_salarios_colaboradores';

    protected $primaryKey = 'scb_id';

    protected $fillable = [
        'scb_ccb_id',
        'scb_vfp_id',
        'scb_unidade',
        'scb_valor',
        'scb_valor_liquido',
        'scb_data_inicio',
        'scb_data_fim',
        'scb_data_cadastro',
    ];

    public function conta()
    {
        return $this->belongsTo('Modulos\RH\Models\ContaColaborador', 'scb_ccb_id');
    }

    public function vinculo()
    {
        return $this->belongsTo('Modulos\RH\Models\VinculoFontePagadora', 'scb_vfp_id');
    }

}