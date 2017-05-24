<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\Curso;
use Carbon\Carbon;
use Auth;
use DB;

class CursoRepository extends BaseRepository
{
    public function __construct(Curso $curso)
    {
        $this->model = $curso;
    }

    /**
     * @param $identifier
     * @param $field
     * @return mixed
     */
    public function lists($identifier, $field, $all = false)
    {
        if (!$all) {
            return $this->model
                ->join('acd_usuarios_cursos', 'ucr_crs_id', '=', 'crs_id')
                ->where('ucr_usr_id', '=', Auth::user()->usr_id)
                ->pluck($field, $identifier)->toArray();
        }

        return $this->model->pluck($field, $identifier)->toArray();
    }

    public function listsByCursoId($cursoId)
    {
        return $this->model
            ->where('crs_id', $cursoId)
            ->pluck('crs_nome', 'crs_id');
    }


    /**
     * Formata datas pt_BR para default MySQL
     * para update de registros
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $data['crs_data_autorizacao'] = Carbon::createFromFormat('d/m/Y', $data['crs_data_autorizacao'])->toDateString();

        return $this->model->where($attribute, '=', $id)->update($data);
    }

    /**
     * Busca um curso específico de acordo com a sua oferta
     * @param $cursodaofertaid
     * @return mixed
     */
    public function listsCursoByOferta($cursodaofertaid)
    {
        return $this->model->where('crs_id', $cursodaofertaid)->pluck('crs_nome', 'crs_id');
    }

    /**
     * Busca um curso específico de acordo com a sua matriz
     * @param $matrizId
     * @return mixed
     */
    public function listsCursoByMatriz($matrizId)
    {
        return $this->model->where('crs_id', $matrizId)->pluck('crs_nome', 'crs_id');
    }

    /**
     * Traz somente os cursos tecnicos
     * @param int $nivelTecnicoId
     * @return mixed
     */
    public function listsCursosTecnicos($nivelTecnicoId = 1)
    {
        return $this->model->where('crs_nvc_id', $nivelTecnicoId)->pluck('crs_nome', 'crs_id');
    }

    public function deleteConfiguracoes($cursoId)
    {
        return DB::table('acd_configuracoes_cursos')->where('cfc_crs_id', '=', $cursoId)->delete();
    }
}
