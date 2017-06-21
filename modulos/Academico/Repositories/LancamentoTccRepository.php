<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\LancamentoTcc;
use DB;
use Carbon\Carbon;
use Modulos\Geral\Models\Anexo;
use Modulos\Geral\Repositories\AnexoRepository;

class LancamentoTccRepository extends BaseRepository
{
    public function __construct(LancamentoTcc $lancamentotcc)
    {
        $this->model = $lancamentotcc;
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

        $data['ltc_data_apresentacao'] = Carbon::createFromFormat('d/m/Y', $data['ltc_data_apresentacao'])->toDateString();

        $collection = $this->model->where($attribute, '=', $id)->get();

        if ($collection) {
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
