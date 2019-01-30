<?php

namespace Modulos\Academico\Repositories;

use Modulos\Academico\Models\MatriculaOfertaDisciplina;
use Modulos\Academico\Models\Turma;
use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\LancamentoTcc;
use DB;
use Carbon\Carbon;
use Modulos\Geral\Models\Anexo;
use Modulos\Geral\Repositories\AnexoRepository;
use Modulos\Academico\Models\ModuloDisciplina;

class LancamentoTccRepository extends BaseRepository
{
    protected $moduloDisciplina;
    protected $matriculaOfertaDisciplina;
    protected $turma;

    public function __construct(LancamentoTcc $lancamentotcc, ModuloDisciplina $moduloDisciplina, MatriculaOfertaDisciplina $matriculaOfertaDisciplina, Turma $turma)
    {
        $this->model = $lancamentotcc;
        $this->moduloDisciplina = $moduloDisciplina;
        $this->matriculaOfertaDisciplina = $matriculaOfertaDisciplina;
        $this->turma = $turma;
    }

    /**
     * @param null $sort
     * @param null $search
     * @return mixed
     */
    public function paginate($sort = null, $search = null)
    {

        //buscar todas as disciplinas do tipo Tcc
        $disciplinas_tcc = $this->moduloDisciplina
            ->where('mdc_tipo_disciplina', '=', 'tcc')->pluck('mdc_id');

        //busca todas as ofertas de curso que possuem alunos matriculados em disciplina do tipo TCC
        $turmas = $this->matriculaOfertaDisciplina
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
            ->join('acd_ofertas_disciplinas', function ($join) {
                $join->on('mof_ofd_id', '=', 'ofd_id');
            })
            ->join('acd_modulos_disciplinas', function ($join) {
                $join->on('ofd_mdc_id', '=', 'mdc_id');
            })
            ->whereIn('mdc_id', $disciplinas_tcc)->pluck('trm_id');

        //Busca todas as turmas que tem alunos matriculados em disciplinas do tipo TCC
        $result = $this->turma
            ->join('acd_ofertas_cursos', function ($join) {
                $join->on('trm_ofc_id', '=', 'ofc_id');
            })
            ->join('acd_cursos', function ($join) {
                $join->on('ofc_crs_id', '=', 'crs_id');
            })
            ->whereIn('trm_id', $turmas);

        if (!empty($search)) {
            foreach ($search as $value) {
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

    public function findBy(array $options, array $select = null)
    {
        $query = $this->model
                        ->join('acd_professores', function ($join) {
                            $join->on('ltc_prf_id', '=', 'prf_id');
                        })
                        ->join('gra_pessoas', function ($join) {
                            $join->on('prf_pes_id', '=', 'pes_id');
                        });

        foreach ($options as $key => $value) {
            $query = $query->where($key, '=', $value);
        }

        if ($select) {
            $query = $query->select($select);
        }

        return $query->get();
    }

    public function findDisciplinaByTurma($turmaId)
    {
        $disciplina = DB::table('acd_turmas')
          ->join('acd_ofertas_disciplinas', 'ofd_trm_id', '=', 'trm_id')
          ->join('acd_modulos_disciplinas', 'ofd_mdc_id', '=', 'mdc_id')
          ->join('acd_disciplinas', 'dis_id', '=', 'mdc_dis_id')
          ->where('mdc_tipo_disciplina', '=', 'tcc')
          ->where('trm_id', '=', $turmaId)->first();

        return $disciplina;
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

        $collection = $this->model->where($attribute, '=', $id)->get();

        if ($collection->count()) {
            foreach ($collection as $obj) {
                $obj->fill($data)->save();
            }

            return $collection->count();
        }

        return 0;
    }

    public function deleteAnexoTcc($lancamentotccId)
    {
        try {
            $anexoId = DB::table('acd_lancamentos_tccs')->where('ltc_id', '=', $lancamentotccId)->pluck('ltc_anx_tcc')->toArray();

            $lancamentoTccObject = $this->model->find($lancamentotccId);

            if ($lancamentoTccObject) {
                $lancamentoTccObject->fill(['ltc_anx_tcc' => null])->save();
            }

            if ($anexoId) {
                $anexoRepository = new AnexoRepository(new Anexo());
                $result = $anexoRepository->deletarAnexo(array_pop($anexoId));
                return $result;
            }
        } catch (\Exception $e) {
            throw $e;
        }

        return false;
    }
}
