<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\Disciplina;
use DB;

class DisciplinaRepository extends BaseRepository
{
    protected $matrizCurricularRepository;

    public function __construct(Disciplina $disciplina, MatrizCurricularRepository $matrizCurricularRepository)
    {
        $this->model = $disciplina;
        $this->matrizCurricularRepository = $matrizCurricularRepository;
    }

    /**
     * Cas
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
     * Busca todas as disciplinas não pertencentes a matriz atual pelo nome da disciplina e filtra as disciplinas de acordo com o nível do curso.
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

        $nivelIds = DB::table('acd_matrizes_curriculares')
            ->select('crs_nvc_id')
            ->join('acd_cursos','mtc_crs_id', '=', 'crs_id')
            ->where('mtc_id', '=', $matriz)
            ->get();

        $niveis = array();
        foreach ($nivelIds as $nivelId) {
            $niveis[] = $nivelId->crs_nvc_id;
        }

        $result = $this->model
            ->join('acd_niveis_cursos', 'dis_nvc_id', 'nvc_id')
            ->where('dis_nome', 'like', "%{$nome}%")
            ->where('dis_nvc_id', '=', $niveis[0])
            ->whereNotIn('dis_id', $disciplinasId)
            ->get();


        if($result)
        {
          return $result;
        }

        return null;
    }

}
