<?php

namespace Modulos\Matriculas\Models;

use Modulos\Core\Model\BaseModel;
use Carbon\Carbon;

class Chamada extends BaseModel
{
    protected $connection = 'mysql1';
    public $table = 'chamadas';

    protected $fillable = [
        'nome',
        'seletivo_id',
        'inicio_matricula',
        'fim_matricula',
        'numero_chamada',
        'tipo_chamada',
    ];

    protected $searchable = [
        'tipo_chamada' => '=',
        'seletivo_id' => '=',
        'nome' => 'like',
    ];

    public function setInicioMatriculaAttribute($value)
    {
        $this->attributes['inicio_matricula'] = Carbon::createFromFormat('d/m/Y H:i:s', $value)->toDateTimeString();
    }

    public function setFimMatriculaAttribute($value)
    {
        $this->attributes['fim_matricula'] = Carbon::createFromFormat('d/m/Y H:i:s', $value)->toDateTimeString();
    }

    public function seletivo()
    {
        return $this->belongsTo(Seletivo::class, 'seletivo_id');
    }
}
