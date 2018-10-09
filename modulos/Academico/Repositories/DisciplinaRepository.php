<?php

namespace Modulos\Academico\Repositories;

use DB;
use Modulos\Academico\Models\Disciplina;
use Modulos\Core\Repository\BaseRepository;

class DisciplinaRepository extends BaseRepository
{
    protected $matrizCurricularRepository;

    public function __construct(Disciplina $disciplina, MatrizCurricularRepository $matrizCurricularRepository)
    {
        $this->model = $disciplina;
        $this->matrizCurricularRepository = $matrizCurricularRepository;
    }

    /**
     * Verifica se nao existe outra disciplina com os
     * mesmos atributos para ser criada
     *
     * @param array $data
     * @param null $id
     * @return bool
     */
    public function validacao(array $data, $id = null)
    {
        $result = $this->model
            ->where('dis_nvc_id', $data['dis_nvc_id'])
            ->where('dis_creditos', $data['dis_creditos'])
            ->where('dis_nome', $data['dis_nome'])
            ->where('dis_carga_horaria', $data['dis_carga_horaria'])->get();

        if ($result->isEmpty()) {
            return true;
        }

        if (!is_null($id)) {
            $result = $result->where('dis_id', $id);

            return !$result->isEmpty();
        }

        return false;
    }

    /**
     *
     * Busca todas as disciplinas não pertencentes a
     * matriz atual pelo nome da disciplina
     * e filtra as disciplinas de acordo com o nível do curso.
     *
     * @param $matriz
     * @param $nome
     * @return null
     */
    public function buscar($matriz, $nome)
    {
        $disciplinasMatriz = $this->matrizCurricularRepository->getDisciplinasByMatrizId($matriz);

        $disciplinasId = [];
        foreach ($disciplinasMatriz as $key => $value) {
            $disciplinasId[] = $value->mdc_dis_id;
        }

        $query = DB::table('acd_matrizes_curriculares')
            ->select('crs_nvc_id')
            ->join('acd_cursos', 'mtc_crs_id', '=', 'crs_id')
            ->where('mtc_id', '=', $matriz)
            ->first();

        $nivel = $query->crs_nvc_id;

        return $this->model
            ->join('acd_niveis_cursos', 'dis_nvc_id', 'nvc_id')
            ->where('dis_nome', 'like', "%{$nome}%")
            ->where('dis_nvc_id', '=', $nivel)
            ->whereNotIn('dis_id', $disciplinasId)
            ->get();
    }

    public function getDisciplinasModulosAnteriores($matrizId, $moduloId)
    {
        $entries = DB::table('acd_modulos_disciplinas')
                    ->join('acd_disciplinas', 'mdc_dis_id', 'dis_id')
                    ->join('acd_niveis_cursos', 'dis_nvc_id', 'nvc_id')
                    ->join('acd_modulos_matrizes', 'mdc_mdo_id', 'mdo_id')
                    ->select('acd_disciplinas.*', 'mdc_id', 'nvc_nome')
                    ->where('mdo_mtc_id', '=', $matrizId)
                    ->where('mdo_id', '<', $moduloId)
                    ->get();

        return $entries;
    }

    public function paginate($sort = null, $search = null)
    {
        $result = $this->model->join('acd_niveis_cursos', 'dis_nvc_id', 'nvc_id');

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

        if (empty($sort)) {
            return $result->orderBy('nvc_id', 'asc')->paginate(15);
        }

        return $result->orderBy($sort['field'], $sort['sort'])->paginate(15);
    }
}
