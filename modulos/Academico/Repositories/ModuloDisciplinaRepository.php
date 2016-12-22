<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\ModuloDisciplina;
use DB;

class ModuloDisciplinaRepository extends BaseRepository
{
    protected $disciplinaRepository;
    protected $matrizCurricularRepository;

    public function __construct(
        ModuloDisciplina $modulodisciplina,
        DisciplinaRepository $disciplina,
        MatrizCurricularRepository $matriz
    ) {
        $this->model = $modulodisciplina;
        $this->disciplinaRepository = $disciplina;
        $this->matrizCurricularRepository = $matriz;
    }

    public function verifyDisciplinaModulo($idDisciplina, $idModulo)
    {
        $disciplina = DB::table('acd_disciplinas')
          ->where('dis_id', '=', $idDisciplina)->pluck('dis_nome', 'dis_id');


        $verificar = DB::table('acd_disciplinas')
          ->join('acd_modulos_disciplinas', 'dis_id', '=', 'acd_modulos_disciplinas.mdc_dis_id')
          ->join('acd_modulos_matrizes', 'acd_modulos_disciplinas.mdc_mdo_id', '=', 'acd_modulos_matrizes.mdo_id')
          ->join('acd_matrizes_curriculares', 'acd_modulos_matrizes.mdo_mtc_id', '=', 'acd_matrizes_curriculares.mtc_id')
          ->where('acd_modulos_disciplinas.mdc_mdo_id', '=', $idModulo)
          ->where('dis_nome', '=', $disciplina[$idDisciplina])->get();

        if ($verificar->isEmpty()) {
            return false;
        };

        return true;
    }

    public function getAllDisciplinasByModulo($moduloId)
    {
        $result = $this->model->join('acd_disciplinas', 'mdc_dis_id', 'dis_id')
            ->join('acd_niveis_cursos', 'dis_nvc_id', 'nvc_id')
            ->where('mdc_mdo_id', '=', $moduloId)->get();

        if ($result->count()) {
            // busca as disciplinas que são pré-requisitos
            for ($i = 0; $i < $result->count(); $i++) {
                $disciplinas = [];
                if (!is_null($result[$i]->mdc_pre_requisitos)) {
                    $ids = json_decode($result[$i]->mdc_pre_requisitos);

                    foreach ($ids as $id) {
                        $disciplina = $this->model
                                            ->join('acd_disciplinas', 'mdc_dis_id', 'dis_id')
                                            ->where('mdc_id', $id)
                                            ->first();
                        if ($disciplina) {
                            $disciplinas[] = $disciplina;
                        }
                    }
                }

                $result[$i]->pre_requisitos = $disciplinas;
            }
        }

        return $result;
    }

    public function verifyDisciplinaAdicionada($data)
    {
        $result = $this->model
              ->where('mdc_dis_id', '=', $data['dis_id']);
    }

    public function updatePreRequisitos($matrizId, $moduloDisciplinaId)
    {
        $query = DB::table('acd_modulos_disciplinas')
                    ->join('acd_modulos_matrizes', 'mdc_mdo_id', 'mdo_id')
                    ->where('mdo_mtc_id', '=', $matrizId)
                    ->update(['mdc_pre_requisitos' => DB::raw('JSON_REMOVE(mdc_pre_requisitos, JSON_UNQUOTE(JSON_SEARCH(mdc_pre_requisitos, "one", "'.$moduloDisciplinaId.'", NULL, "$")))')]);


        return $query;
    }

    public function create(array $dados)
    {
        // buscar dados da disciplina
        $disciplina = $this->disciplinaRepository->find($dados['dis_id']);

        // função que verifica se a disciplina já exite na matriz
        $disciplinaExists = $this->matrizCurricularRepository->verifyIfDisciplinaExistsInMatriz($dados['mtc_id'], $dados['dis_id']);

        // Se a disciplina existir, retornar uma mensagem de erro
        if ($disciplinaExists) {
            return array('type' => 'error', 'message' => 'Disciplina Duplicada');
        }

        // função que verifica se já existe alguma disciplina com o mesmo nome na matriz
        $disciplinaNameExists = $this->matrizCurricularRepository->verifyIfNomeDisciplinaExistsInMatriz($dados['mtc_id'], $disciplina->dis_nome);

        // Se existir uma disciplina com mesmo nome, retorna uma mesagem de erro
        if ($disciplinaNameExists) {
            return array('type' => 'error', 'message' => 'Já existe uma disciplina com esse nome');
        }

        // verifica se o tipo da disciplina é tcc
        if ($dados['tipo_disciplina'] == 'tcc') {

            // caso seja, verifica se já existe alguma disciplina cadastrada como tcc na matriz
            $disciplinaTccExists = $this->matrizCurricularRepository->verifyIfExistsDisciplinaTccInMatriz($dados['mtc_id']);

            // se existir, envia uma mensagem de erro
            if ($disciplinaTccExists) {
                return array('type' => 'error', 'message' => 'Já existe uma disciplina do tipo TCC cadastrada nessa matriz');
            }
        }
        
        // verifica se veio disciplina pré-requisitos
        if (!empty($dados['pre_requisitos'])) {

            // pega os id's da disciplinas que podem ser adicionadas como pré-requisitos
            $disciplinasAptasPreRequisitos = $this->disciplinaRepository
                                                    ->getDisciplinasModulosAnteriores($dados['mtc_id'], $dados['mod_id'])
                                                    ->pluck('mdc_id')->toArray();

            // verifica, uma a uma, se existe alguma que não está apta a ser cadastrada como pré-requisito
            foreach ($dados['pre_requisitos'] as $preRequisito) {
                if (!in_array($preRequisito, $disciplinasAptasPreRequisitos)) {
                    return array('type' => 'error', 'message' => 'Disciplina(s) inválidas para pré-requisito(s)');
                }
            }
        }

        $modulodisciplina['mdc_dis_id'] = $dados['dis_id'];
        $modulodisciplina['mdc_mdo_id'] = $dados['mod_id'];
        $modulodisciplina['mdc_tipo_avaliacao'] = $dados['tipo_avaliacao'];
        $modulodisciplina['mdc_tipo_disciplina'] = $dados['tipo_disciplina'];
        $modulodisciplina['mdc_pre_requisitos'] = (empty($dados['pre_requisitos'])) ? null : json_encode($dados['pre_requisitos']);

        $disciplinaCreate = $this->model->create($modulodisciplina);

        return array('type' => 'success', 'data' => ['mdc_id' => $disciplinaCreate->mdc_id]);
    }
}
