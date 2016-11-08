<?php
namespace Modulos\Geral\Models;

use Illuminate\Support\Facades\Storage;
use Modulos\Core\Model\BaseModel;
use DB;

class Configuracao extends BaseModel
{
    public $timestamps = false;

    protected $table = 'gra_configuracoes';

    protected $primaryKey = 'cnf_id';

    protected $fillable = [
        'cnf_mod_id',
        'cnf_nome',
        'cnf_valor',
    ];

    protected $searchable = [
        'cnf_nome' => 'like'
    ];

    public static function destroy($config)
    {
        return;
    }
}
