<?php

namespace Modulos\Academico\Repositories;

use Modulos\Academico\Models\OfertaDisciplina;
use Modulos\Core\Repository\BaseRepository;
use Auth;

class OfertaDisciplinaRepository extends BaseRepository
{
    public function __construct(OfertaDisciplina $ofertaDisciplina)
    {
        $this->model = $ofertaDisciplina;
    }

    public function paginate($sort = null, $search = null)
    {
        $result = $this->model->join('acd_modulos_disciplinas', function ($join) {
            $join->on('ofd_mdc_id', '=', 'mdc_id');
        })
            ->join('acd_disciplinas', function ($join) {
                $join->on('mdc_dis_id', '=', 'dis_id');
            })
            ->join('acd_periodos_letivos', function ($join) {
                $join->on('ofd_per_id', '=', 'per_id');
            });

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

        if (!empty($sort)) {
            $result = $result->orderBy($sort['field'], $sort['sort']);
        }

        $result = $result->paginate(15);

        return $result;
    }

    public function findAll(array $options) {
        $result = $this->model
                        ->join('acd_modulos_disicplinas', function ($join) {
                            $join->on('');
                        });
    }

    public function verifyDisciplinaTurmaPeriodo($turmaId, $periodoId)
    {
        $exists = $this->model->where('ofd_trm_id', $turmaId)
                           ->where('ofd_per_id', $periodoId)
                           ->first();

        if ($exists) {
            return true;
        }

        return false;

    }


}
