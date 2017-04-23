<?php
namespace Modulos\Geral\Models;

use Modulos\Core\Model\BaseModel;

class TitulacaoInformacao extends BaseModel
{
    protected $table = 'gra_titulacoes_informacoes';

    protected $primaryKey = 'tin_id';

    protected $fillable = [
        'tin_pes_id',
        'tin_tit_id',
        'tin_titulo',
        'tin_instituicao',
        'tin_instituicao_sigla',
        'tin_instituicao_sede',
        'tin_anoinicio',
        'tin_anofim'
    ];

    public function pessoa()
    {
        return $this->belongsTo('Modulos\Geral\Models\Pessoa', 'tin_pes_id');
    }

    public function titulacao()
    {
        return $this->belongsTo('Modulos\Geral\Models\Titulacao', 'tin_tit_id');
    }

    // Accessors
    public function getTinAnofimAttribute($value)
    {
        if ($value == 0) {
            return null;
        }

        return $value;
    }
}
