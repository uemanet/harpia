<?php

namespace Modulos\Academico\Repositories;

use Illuminate\Support\Facades\DB;
use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\Professor;

class ProfessorRepository extends BaseRepository
{
    public function __construct(Professor $professor)
    {
        $this->model = $professor;
    }

    /**
     * Retorna listas de pares com dados de tabelas
     * @param string $identifier
     * @param string $field
     */
    public function lists($identifier, $field, $all = false)
    {
        $sql = DB::table('gra_pessoas')
            ->join('acd_professores', 'pes_id', '=', 'acd_professores.prf_pes_id');

        if(!$all) {
            $sql = $sql->leftJoin('acd_centros', 'cen_prf_diretor', '=', 'prf_id')
                ->leftJoin('acd_departamentos', 'dep_prf_diretor', '=', 'prf_id')
                ->leftJoin('acd_cursos', 'crs_prf_diretor', '=', 'prf_id')
                ->whereNull('cen_prf_diretor')
                ->whereNull('dep_prf_diretor')
                ->whereNull('crs_prf_diretor');
        }

        $entries = $sql->select($identifier, $field)->pluck($field, $identifier);

        return collect($entries);
    }
}
