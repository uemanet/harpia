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
        'jus_descrição',
    ];

    protected  $searchable = [
        'jus_data' => 'like'
    ];

    // Accessors
    public function getJusDataAttribute($value)
    {
        if (!is_null($value)) {
            setlocale(LC_ALL, 'pt_BR');
            return Carbon::createFromFormat('Y-m-d', $value)->formatLocalized('%d/%m/%Y');
        }
    }

    // Mutators
    public function setJusDataAttribute($value)
    {
        $this->attributes['jus_data'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();
    }
}