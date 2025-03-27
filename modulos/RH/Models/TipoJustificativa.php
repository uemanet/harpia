<?php

namespace Modulos\RH\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoJustificativa extends Model
{
    use HasFactory;

    protected $table = 'reh_tipo_justificativas';

    protected $fillable = ['tipo_jus_descricao'];
}
