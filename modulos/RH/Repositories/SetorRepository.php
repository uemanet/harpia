<?php


namespace Modulos\RH\Repositories;


use Modulos\Core\Repository\BaseRepository;
use Modulos\RH\Models\Setor;

class SetorRepository extends BaseRepository
{
    public function __construct(Setor $setor)
    {
        $this->model = $setor;
    }
}