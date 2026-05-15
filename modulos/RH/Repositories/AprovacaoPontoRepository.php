<?php

namespace Modulos\RH\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\RH\Models\AprovacaoPonto;

class AprovacaoPontoRepository extends BaseRepository
{
    public function __construct(AprovacaoPonto $aprovacaoPonto)
    {
        $this->model = $aprovacaoPonto;
    }

    public function buscarPorEvento(int $evaId): ?AprovacaoPonto
    {
        return $this->model->where('apr_eva_id', $evaId)->orderBy('apr_data_aprovacao', 'desc')->first();
    }
}
