<?php

namespace Modulos\Geral\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Geral\Models\TitulacaoInformacao;

class TitulacaoInformacaoRepository extends BaseRepository
{
    public function __construct(TitulacaoInformacao $titulacaoInformacao)
    {
        $this->model = $titulacaoInformacao;
    }

    public function findBy(array $options, array $select = null, array $order = null)
    {
        $query = $this->model
                    ->join('gra_titulacoes', 'tin_tit_id', '=', 'tit_id')
                    ->join('gra_pessoas', 'tin_pes_id', '=', 'pes_id');

        foreach ($options as $key => $value) {
            $query = $query->where($key, '=', $value);
        }

        if ($select) {
            $query = $query->select($select);
        }

        if ($order) {
            foreach ($order as $key => $value) {
                $query = $query->orderBy($key, $value);
            }
        }

        return $query->get();
    }
}
