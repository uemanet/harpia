<?php

namespace Modulos\Academico\Repositories;

use Modulos\Academico\Models\OfertaDisciplina;
use Modulos\Core\Repository\BaseRepository;
use Auth;
use DB;

class OfertaDisciplinaRepository extends BaseRepository
{
    public function __construct(OfertaDisciplina $ofertaDisciplina)
    {
        $this->model = $ofertaDisciplina;
    }

    public function findAll(array $options, array $select = null)
    {
        $query = $this->model
                        ->join('acd_modulos_disciplinas', function ($join) {
                            $join->on('ofd_mdc_id', '=', 'mdc_id');
                        })
                        ->join('acd_disciplinas', function ($join) {
                            $join->on('mdc_dis_id', '=', 'dis_id');
                        })
                        ->join('acd_professores', function ($join) {
                            $join->on('ofd_prf_id', '=', 'prf_id');
                        })
                        ->join('gra_pessoas', function ($join) {
                            $join->on('prf_pes_id', '=', 'pes_id');
                        });

        if (!empty($options)) {
            foreach ($options as $key => $value) {
                $query = $query->where($key, '=', $value);
            }
        }

        if (!is_null($select)) {
            $query = $query->select($select);
        }

        return $query->get();
    }

    public function verifyDisciplinaTurmaPeriodo($turmaId, $periodoId, $disciplinaId)
    {
        $exists = $this->model->where('ofd_trm_id', $turmaId)
                              ->where('ofd_per_id', $periodoId)
                              ->where('ofd_mdc_id', $disciplinaId)
                              ->first();

        if ($exists) {
            return true;
        }

        return false;
    }

    /**
     * Verifica se dada oferta foi excluida do Moodle
     * @param $ofertaid
     * @return bool
     */
    public function excludedFromMoodle($ofertaid)
    {
        $result = DB::table('int_sync_moodle')
            ->where('sym_table', '=', $this->model->getTable())
            ->where('sym_table_id', '=', $ofertaid)
            ->where('sym_action', '=', 'DELETE')
            ->where('sym_status', '=', 2)
            ->first();

        if ($result) {
            return true;
        }

        return false;
    }
}
