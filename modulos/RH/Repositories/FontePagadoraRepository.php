<?php


namespace Modulos\RH\Repositories;


use Modulos\Core\Repository\BaseRepository;
use Modulos\RH\Models\FontePagadora;

class FontePagadoraRepository extends BaseRepository
{
    public function __construct(FontePagadora $fontePagadora)
    {
        $this->model = $fontePagadora;
    }
}