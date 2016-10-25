<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\Disciplina;

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
     * TODO: Não buscar disciplinas com o nível diferente do nível do curso do qual a matriz pertence
     *
     * Busca todas as disciplinas não pertencentes a matriz atual pelo nome da disciplina
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

        $result = $this->model
            ->join('acd_niveis_cursos', 'dis_nvc_id', 'nvc_id')
            ->where('dis_nome', 'like', "%{$nome}%")
            ->whereNotIn('dis_id', $disciplinasId)
            ->get();

        if($result)
        {
          return $result;
        }

        return null;
    }

}
