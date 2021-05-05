<?php


namespace Modulos\RH\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\RH\Models\MatriculaColaborador;

class MatriculaColaboradorRepository extends BaseRepository
{
    public function __construct(MatriculaColaborador $matricula_colaborador)
    {
        $this->model = $matricula_colaborador;
    }

    public function getMatriculasByColId($col_id){
        return $this->model->where('mtc_col_id', $col_id)->get();
    }
}