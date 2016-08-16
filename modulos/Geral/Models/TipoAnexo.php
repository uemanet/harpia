<?php
namespace Modulos\Geral\Models;


use Modulos\Core\Model\BaseModel;

class TipoAnexo extends BaseModel
{
    protected $table = 'gra_tipos_anexos';

    protected $primaryKey = 'tax_id';

    protected $fillable = ['tax_nome'];

}