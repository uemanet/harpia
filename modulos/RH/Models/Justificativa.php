<?php


namespace Modulos\RH\Models;

use Carbon\Carbon;
use Modulos\Core\Model\BaseModel;

class Justificativa extends BaseModel
{
    protected $table = 'reh_justificativas';

    protected  $primaryKey = 'jus_id';

    protected  $fillable = [
        'jus_anx_id',
        'jus_htr_id',
        'jus_horas',
        'jus_data',
        'jus_data_fim',
        'jus_descricao',
        'jus_tipo_id',
    ];

    protected  $searchable = [
        'jus_data' => 'like',
        'jus_data_fim' => 'like'
    ];

    public function tipo()
    {
        return $this->belongsTo(TipoJustificativa::class, 'jus_tipo_id', 'tipo_jus_id');
    }


    public function anexo()
    {
        return $this->belongsTo('Modulos\Geral\Models\Anexo', 'jus_anx_id', 'anx_id');
    }

    public function horaTrabalhada()
    {
        return $this->belongsTo('Modulos\RH\Models\HoraTrabalhada', 'jus_htr_id', 'htr_id');
    }

    // Accessors
    public function getJusDataAttribute($value)
    {
        if (!is_null($value)) {
            setlocale(LC_ALL, 'pt_BR');
            return Carbon::createFromFormat('Y-m-d', $value)->formatLocalized('%d/%m/%Y');
        }
    }

    public function getJusDataFimAttribute($value)
    {

        if (!$value || $value === '0000-00-00') {
            return 'Data Inválida';
        }

        setlocale(LC_ALL, 'pt_BR');
        return Carbon::createFromFormat('Y-m-d', $value)->formatLocalized('%d/%m/%Y');
    }

    // Mutators
    public function setJusDataAttribute($value)
    {
        $this->attributes['jus_data'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();
    }

    public function setJusDataFimAttribute($value)
    {
        $this->attributes['jus_data_fim'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();
    }
}