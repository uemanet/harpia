<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\Tutor;
use DB;

class TutorRepository extends BaseRepository
{
    public function __construct(Tutor $tutor)
    {
        $this->model = $tutor;
    }

    public function listsTutorPessoa()
    {
        $tutores = DB::table('acd_tutores')
           ->join('gra_pessoas', 'tut_pes_id', '=', 'pes_id')
           ->pluck('pes_nome', 'tut_id');
        return $tutores;
    }
}
