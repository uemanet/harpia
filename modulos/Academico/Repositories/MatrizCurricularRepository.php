<?php
declare(strict_types=1);

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\MatrizCurricular;

class MatrizCurricularRepository extends BaseRepository
{
    public function __construct(MatrizCurricular $matrizCurricular)
    {
        parent::__construct($matrizCurricular);
    }

    /**
     * Pagina apenas as matrizes pelo curso
     * @param array|null $requestParameters
     * @return mixed
     */
    public function paginateRequestByCurso($cursoid, array $requestParameters = null)
    {
        $sort = array();
        if (!empty($requestParameters['field']) && !empty($requestParameters['sort'])) {
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

    public function getDisciplinasByMatrizId($matrizCurricularId, array $options = [])
    {
        $query = $this->model
            ->join('acd_modulos_matrizes', 'mdo_mtc_id', 'mtc_id')
            ->join('acd_modulos_disciplinas', 'mdc_mdo_id', 'mdo_id')
            ->where('mtc_id', $matrizCurricularId);

        if (!empty($options)) {
            foreach ($options as $key => $value) {
                $query = $query->where($key, '=', $value);
            }
        }

        return $query->get();
    }

    public function verifyIfDisciplinaExistsInMatriz($matrizId, $disciplinaId, $tcc = false): bool
    {
        $exists = \DB::table('acd_modulos_disciplinas')
            ->join('acd_modulos_matrizes', 'mdo_id', '=', 'mdc_mdo_id')
            ->select('mdc_dis_id')
            ->where('mdo_mtc_id', $matrizId)
            ->where('mdc_dis_id', $disciplinaId)
            ->where(function ($query) use ($tcc) {
                if ($tcc) {
                    $query->where('mdc_tipo_disciplina', '=', 'tcc');
                }
            });

        $exists = $exists->first();

        return (bool)$exists;
    }

    public function verifyIfNomeDisciplinaExistsInMatriz($matrizId, $nomeDisciplina): bool
    {
        $exists = \DB::table('acd_modulos_disciplinas')
            ->join('acd_modulos_matrizes', 'mdo_id', '=', 'mdc_mdo_id')
            ->join('acd_disciplinas', 'dis_id', '=', 'mdc_dis_id')
            ->select('mdc_dis_id')
            ->where('mdo_mtc_id', $matrizId)
            ->where('dis_nome', $nomeDisciplina)
            ->first();

        return (bool)$exists;
    }

    public function verifyIfExistsDisciplinaTccInMatriz($matrizId): bool
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

        return ($query > 0);
    }
}
