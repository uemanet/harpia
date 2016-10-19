<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\Vinculo;
use DB;

class VinculoRepository extends BaseRepository
{
    public function __construct(Vinculo $vinculo)
    {
        $this->model = $vinculo;
    }

    /**
     * Retorna todos os cursos aos quais o usuario esta vinculado.
     *
     * @param $usuarioId
     */
    public function getCursosVinculados($usuarioId)
    {
        return $this->model
            ->select('ucr_id', 'crs_id', 'crs_nome', 'crs_sigla')
            ->join('acd_cursos', 'ucr_crs_id', '=', 'crs_id')
            ->where('ucr_usr_id', '=', $usuarioId)->paginate(15);
    }


    public function getCursosDisponiveis($usuarioId)
    {
        return DB::table('acd_cursos')
                  ->select('crs_id', 'crs_nome', 'crs_sigla')
                  ->whereNotIn('crs_id', DB::table('acd_cursos')
                            ->select('crs_id')
                            ->join('acd_usuarios_cursos', 'ucr_crs_id', '=', 'crs_id')
                            ->where('ucr_usr_id', '=', $usuarioId)
                            ->pluck('crs_id'))
                  ->pluck('crs_nome', 'crs_id');
    }

    /**
     * Verifica se o usuario tem vinculo com o curso
     * @param $usuarioId
     * @param $cursoId
     * @return bool
     */
    public function userHasVinculo($usuarioId, $cursoId)
    {
        $result = DB::table('acd_usuarios_cursos')
                     ->where([
                         ['ucr_usr_id', '=', $usuarioId],
                         ['ucr_crs_id', '=', $cursoId]
                     ])->get();
        return !$result->isEmpty();
    }
}
