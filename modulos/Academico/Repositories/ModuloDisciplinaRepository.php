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

    public function getAllDisciplinasNotOfertadasByModulo($moduloId, $turmaId, $periodoId)
    {
        // busca todas as disciplinas ofertadas do modulo, de acordo com a turma e periodo letivo
        $disciplinasOfertadas = DB::table('acd_ofertas_disciplinas')
            ->join('acd_modulos_disciplinas', 'ofd_mdc_id', 'mdc_id')
            ->where([
                ['mdc_mdo_id', '=', $moduloId],
                ['ofd_trm_id', '=', $turmaId],
                ['ofd_per_id', '=', $periodoId]
            ])
            ->pluck('mdc_id');


        $query = $this->model->join('acd_disciplinas', 'mdc_dis_id', 'dis_id')
            ->join('acd_niveis_cursos', 'dis_nvc_id', 'nvc_id')
            ->where('mdc_mdo_id', '=', $moduloId);

        if ($disciplinasOfertadas) {
            $query = $query->whereNotIn('mdc_id', $disciplinasOfertadas);
        }

        $result = $query->get();

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

    public function getAllTurmasWithTcc($id)
    {
        $result = $this->model
            ->where('mdc_tipo_disciplina', '=', 'tcc')
            ->join('acd_disciplinas', 'mdc_dis_id', 'dis_id')
            ->join('acd_niveis_cursos', 'acd_disciplinas.dis_nvc_id', 'nvc_id')
            ->where('mdc_mdo_id', '=', $id)->get();

        return $result;
    }

    public function verifyDisciplinaAdicionada($data)
    {
        $result = $this->model
              ->where('mdc_dis_id', '=', $data['dis_id']);

        return $result;
    }

    public function paginate($sort = null, $search = null)
    {
        $result = $this->model
            ->join('acd_disciplinas', function ($join) {
                $join->on('mdc_dis_id', '=', 'dis_id');
            })
            ->join('acd_ofertas_disciplinas', function ($join) {
                $join->on('ofd_mdc_id', '=', 'mdc_id');
            })
            ->join('acd_matriculas_ofertas_disciplinas', function ($join) {
                $join->on('mof_ofd_id', '=', 'ofd_id');
            })
            ->join('acd_matriculas', function ($join) {
                $join->on('mof_mat_id', '=', 'mat_id');
            })
            ->join('acd_turmas', function ($join) {
                $join->on('mat_trm_id', '=', 'trm_id');
            })
            ->join('acd_ofertas_cursos', function ($join) {
                $join->on('trm_ofc_id', '=', 'ofc_id');
            })
            ->join('acd_cursos', function ($join) {
                $join->on('ofc_crs_id', '=', 'crs_id');
            })
            ->where('mdc_tipo_disciplina', '=', 'tcc')
            ->groupby('trm_id')->distinct();

        if (!empty($search)) {
            foreach ($search as $value) {
                if ($value['field'] == 'pes_cpf') {
                    $result = $result->where('doc_conteudo', '=', $value['term']);
                    continue;
                }

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

        $result = $result->paginate(15);

        return $result;
    }

    public function update(array $data, $id, $attribute = null)
    {
        $entry = $this->find($id);

        $modulo = $entry->modulo;
        $matriz = $entry->modulo->matriz;

        // Se alterada para tcc, verificar se ha disciplina de tcc na matriz
        if ($data['mdc_tipo_disciplina'] == 'tcc') {
            // caso seja, verifica se já existe alguma disciplina cadastrada como tcc na matriz
            $disciplinaTccExists = $this->matrizCurricularRepository->verifyIfExistsDisciplinaTccInMatriz($matriz->mtc_id);

            // se existir, envia uma mensagem de erro
            if ($disciplinaTccExists) {
                return array('type' => 'error', 'message' => 'Já existe uma disciplina do tipo TCC cadastrada nessa matriz');
            }
        }

        // Pre-requisitos
        if (!empty($data['mdc_pre_requisitos'])) {

            // pega os id's da disciplinas que podem ser adicionadas como pré-requisitos
            $disciplinasAptasPreRequisitos = $this->disciplinaRepository
                ->getDisciplinasModulosAnteriores($matriz->mtc_id, $modulo->mdo_id)
                ->pluck('mdc_id')->toArray();

            // verifica, uma a uma, se existe alguma que não está apta a ser cadastrada como pré-requisito
            foreach ($data['mdc_pre_requisitos'] as $preRequisito) {
                if (!in_array($preRequisito, $disciplinasAptasPreRequisitos)) {
                    return array('type' => 'error', 'message' => 'Disciplina(s) inválidas para pré-requisito(s)');
                }
            }
        }

        $data['mdc_pre_requisitos'] = (empty($data['mdc_pre_requisitos'])) ? null : json_encode($data['mdc_pre_requisitos']);
        return parent::update($data, $id, $attribute);
    }

    public function updatePreRequisitos($matrizId, $moduloDisciplinaId)
    {
        $registros = $this->model->join('acd_modulos_matrizes', 'mdc_mdo_id', '=', 'mdo_id')
                                ->where('mdo_mtc_id', '=', $matrizId)
                                ->whereNotNull('mdc_pre_requisitos')
                                ->get();

        if ($registros) {
            try {
                DB::beginTransaction();

                foreach ($registros as $obj) {
                    $arrayPreRequisitos = json_decode($obj->mdc_pre_requisitos, true);

                    if (gettype(array_search($moduloDisciplinaId, $arrayPreRequisitos)) != 'boolean') {
                        $key = array_search($moduloDisciplinaId, $arrayPreRequisitos);
                        unset($arrayPreRequisitos[$key]);

                        $obj->fill([
                            'mdc_pre_requisitos' => json_encode($arrayPreRequisitos)
                        ])->save();
                    }
                }

                DB::commit();

                return $registros->count();
            } catch (\Exception $e) {
                DB::rollback();
                return 0;
            }
        }

        return 0;
    }

    public function getDisciplinasPreRequisitos($moduloDisciplinaId)
    {
        $preRequisitos = [];

        $disciplina = $this->find($moduloDisciplinaId);

        if (!is_null($disciplina->mdc_pre_requisitos)) {
            $requisitos = json_decode($disciplina->mdc_pre_requisitos);

            foreach ($requisitos as $req) {
                $result = $this->model
                    ->join('acd_disciplinas', 'mdc_dis_id', 'dis_id')
                    ->where('mdc_id', $req)
                    ->first();

                if ($result) {
                    $preRequisitos[] = $result;
                }
            }
        }

        return $preRequisitos;
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
        $modulodisciplina['mdc_tipo_disciplina'] = $dados['tipo_disciplina'];
        $modulodisciplina['mdc_pre_requisitos'] = (empty($dados['pre_requisitos'])) ? null : json_encode($dados['pre_requisitos']);

        $disciplinaCreate = $this->model->create($modulodisciplina);

        return array('type' => 'success', 'data' => ['mdc_id' => $disciplinaCreate->mdc_id]);
    }
}
