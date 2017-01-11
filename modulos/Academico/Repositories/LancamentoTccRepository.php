<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\LancamentoTcc;
use DB;
use Carbon\Carbon;

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
}
