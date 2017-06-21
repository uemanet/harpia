<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\MatrizCurricular;
use Carbon\Carbon;

class MatrizCurricularRepository extends BaseRepository
{
    public function __construct(MatrizCurricular $matrizCurricular)
    {
        $this->model = $matrizCurricular;
    }

    /**
     * Formata datas pt_BR para default MySQL
     * para update de registros
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute = null)
    {
        if (!$attribute) {
            $attribute = $this->model->getKeyName();
        }

        $data['mtc_data'] = Carbon::createFromFormat('d/m/Y', $data['mtc_data'])->toDateString();

        $collection = $this->model->where($attribute, '=', $id)->get();

        if ($collection) {
            foreach ($collection as $obj) {
                $obj->fill($data)->save();
            }

            return $collection->count();
        }

        return 0;
    }

    /**
     * Pagina apenas as matrizes pelo curso
     * @param array|null $requestParameters
     * @return mixed
     */
    public function paginateRequestByCurso($cursoid, array $requestParameters = null)
    {
        $sort = array();
        if (!empty($requestParameters['field']) and !empty($requestParameters['sort'])) {
            $sort = [
                'field' => $requestParameters['field'],
                'sort' => $requestParameters['sort']
            ];
            return $this->model->where('mtc_crs_id', '=', $cursoid)
                               ->orderBy($sort['field'], $sort['sort'])
                               ->paginate(15);
        }
        return $this->model->where('mtc_crs_id', '=', $cursoid)->paginate(15);
    }

    /**
     * Busca todas as matrizes de acordo com o curso informado
     *
     * @param $cursoid
     *
     * @return mixed
     */
    public function findAllByCurso($cursoid)
    {
        return $this->model->where('mtc_crs_id', $cursoid)->get(['mtc_id', 'mtc_titulo']);
    }

    public function findByOfertaCurso($ofc_id)
    {
        return $this->model
                    ->join('acd_ofertas_cursos', 'ofc_mtc_id', '=', 'mtc_id')
                    ->select('acd_matrizes_curriculares.*')
                    ->where('ofc_id', '=', $ofc_id)
                    ->first();
    }

    /**
     * Busca todas as matrizes de acordo com o curso informado e retorna como lists para popular um field select
     *
     * @param $cursoid
     *
     * @return mixed
     */
    public function listsAllByCurso($cursoid)
    {
        return $this->model->where('mtc_crs_id', $cursoid)->pluck('mtc_titulo', 'mtc_id');
    }

    /**
     * Lista a matriz pelo id
     *
     * @param $id
     * @return mixed
     */
    public function listsAllById($id)
    {
        return $this->model->where('mtc_id', $id)->pluck('mtc_titulo', 'mtc_id');
    }

    public function getDisciplinasByMatrizId($matrizCurricularId)
    {
        return $this->model
            ->join('acd_modulos_matrizes', 'mdo_mtc_id', 'mtc_id')
            ->join('acd_modulos_disciplinas', 'mdc_mdo_id', 'mdo_id')
            ->where('mtc_id', $matrizCurricularId)
            ->get();
    }

    public function verifyIfDisciplinaExistsInMatriz($matrizId, $disciplinaId)
    {
        $exists = \DB::table('acd_modulos_disciplinas')
            ->join('acd_modulos_matrizes', 'mdo_id', '=', 'mdc_mdo_id')
            ->select('mdc_dis_id')
            ->where('mdo_mtc_id', $matrizId)
            ->where('mdc_dis_id', $disciplinaId)
            ->first();

        if ($exists) {
            return true;
        }

        return false;
    }

    public function verifyIfNomeDisciplinaExistsInMatriz($matrizId, $nomeDisciplina)
    {
        $exists = \DB::table('acd_modulos_disciplinas')
            ->join('acd_modulos_matrizes', 'mdo_id', '=', 'mdc_mdo_id')
            ->join('acd_disciplinas', 'dis_id', '=', 'mdc_dis_id')
            ->select('mdc_dis_id')
            ->where('mdo_mtc_id', $matrizId)
            ->where('dis_nome', $nomeDisciplina)
            ->first();

        if ($exists) {
            return true;
        }

        return false;
    }

    public function verifyIfExistsDisciplinaTccInMatriz($matrizId)
    {
        $query = $this->model
                        ->join('acd_modulos_matrizes', function ($join) {
                            $join->on('mdo_mtc_id', '=', 'mtc_id');
                        })
                        ->join('acd_modulos_disciplinas', function ($join) {
                            $join->on('mdc_mdo_id', '=', 'mdo_id');
                        })
                        ->where('mtc_id', '=', $matrizId)
                        ->where('mdc_tipo_disciplina', '=', 'tcc')
                        ->count();

        if ($query > 0) {
            return true;
        }

        return false;
    }
}
