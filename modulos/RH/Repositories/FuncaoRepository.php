<?php


namespace Modulos\RH\Repositories;


use Modulos\Core\Repository\BaseRepository;
use Modulos\RH\Models\Funcao;

class FuncaoRepository extends BaseRepository
{
    public function __construct(Funcao $funcao)
    {
        $this->model = $funcao;
    }
}