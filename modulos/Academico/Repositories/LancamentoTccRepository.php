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
    public function update(array $data, $id, $attribute = "id")
    {
        $data['ltc_data_apresentacao'] = Carbon::createFromFormat('d/m/Y', $data['ltc_data_apresentacao'])->toDateString();

        return $this->model->where($attribute, '=', $id)->update($data);
    }

    public function deleteTcc($lancamentotccId)
    {
        try {
            $anexoId = DB::table('acd_lancamentos_tccs')->where('ltc_id', '=', $lancamentotccId)->pluck('ltc_anx_tcc')->toArray();

            $this->model->where('ltc_id', '=', $lancamentotccId)->update(['ltc_anx_tcc' => null]);

            if ($anexoId) {
                $anexoRepository = new AnexoRepository(new Anexo());
                $result = $anexoRepository->deletarAnexo(array_pop($anexoId));
                return $result;
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
