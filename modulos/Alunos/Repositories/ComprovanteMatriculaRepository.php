<?php

namespace Modulos\Alunos\Repositories;

use Modulos\Alunos\Models\ComprovanteMatricula;
use Modulos\Core\Repository\BaseRepository;

class ComprovanteMatriculaRepository extends BaseRepository
{
    public function __construct(ComprovanteMatricula $comprovanteMatricula)
    {
        $this->model = $comprovanteMatricula;
    }

    public function getComprovanteMatricula($matriculaId)
    {
        return $this->model
            ->where('aln_mat_id', $matriculaId)
            ->where('created_at', '>', date('d.m.Y', strtotime("-1 days")))
            ->first();
    }
}
