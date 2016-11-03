<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\Curso;
use Carbon\Carbon;
use Auth;

class CursoRepository extends BaseRepository
{
    public function __construct(Curso $curso)
    {
        $this->model = $curso;
    }


    /**
     * Paginate
     * @param null $sort
     * @param null $search
     * @return mixed
     */
    public function paginate($sort = null, $search = null)
    {
        $result = $this->model
            ->join('acd_usuarios_cursos', 'ucr_crs_id', '=', 'crs_id')
            ->where('ucr_usr_id', '=', Auth::user()->usr_id);

        if (!empty($search)) {
            foreach ($search as $key => $value) {
                switch ($value['type']) {
                    case 'like':
                        $result = $result->where($value['field'], $value['type'], "%{$value['term']}%");
                        break;
                    default:
                        $result = $result->where($value['field'], $value['type'], $value['term']);
                }
            }
        }

        if (!empty($sort)) {
            $result = $result->orderBy($sort['field'], $sort['sort']);
        }

        return $result->paginate(15);
    }

    /**
     * @param $identifier
     * @param $field
     * @return mixed
     */
    public function lists($identifier, $field)
    {
        return $this->model
            ->join('acd_usuarios_cursos', 'ucr_crs_id', '=', 'crs_id')
            ->where('ucr_usr_id', '=', Auth::user()->usr_id)
            ->pluck($field, $identifier);
    }

    public function listsByCursoId($cursoId)
    {
        return $this->model
            ->join('acd_usuarios_cursos', 'ucr_crs_id', '=', 'crs_id')
            ->where('ucr_usr_id', '=', Auth::user()->usr_id)
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
}
