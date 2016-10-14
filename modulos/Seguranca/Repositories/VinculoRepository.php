<?php

namespace modulos\Seguranca\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Seguranca\Models\Vinculo;

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
    public function getCursos($usuarioId)
    {
        /*
        return DB::table('acd_cursos')
            ->select('crs_id', 'crs_nome', 'crs_sigla')
            ->join('acd_usuarios_cursos', 'crs_id', '=', 'ucr_crs_id')
            ->where('ucr_usr_id', '=', $usuarioId)->get();
        */

        return $this->model
            ->select('crs_id', 'crs_nome', 'crs_sigla')
            ->join('acd_cursos', 'ucr_crs_id', '=', 'crs_id')
            ->where('ucr_usr_id', '=', $usuarioId)->paginate(15);
    }
}
