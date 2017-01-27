<?php

namespace Modulos\Integracao\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Integracao\Models\AmbienteVirtual;
use DB;

class AmbienteVirtualRepository extends BaseRepository
{
    public function __construct(AmbienteVirtual $ambientevirtual)
    {
        $this->model = $ambientevirtual;
    }

    public function verifyIfExistsAmbienteTurma($ambienteId, $turmaId)
    {
        $exists = DB::table('int_ambientes_turmas')
                  ->where('atr_trm_id', '=', $turmaId)
                  ->first();

        if ($exists) {
            return true;
        }

        return false;
    }

    public function getAmbienteByTurma($turmaId)
    {
        $result = DB::table('int_ambientes_virtuais')
            ->select(DB::raw('amb_id as id, amb_url as url, asr_token as token'))
            ->join('int_ambientes_turmas', 'amb_id', '=', 'atr_amb_id')
            ->join('int_ambientes_servicos', 'amb_id', '=', 'asr_amb_id')
            ->where('atr_trm_id', '=', $turmaId)
            ->where('asr_ser_id', '=', 2)
            ->get()->toArray();

        return array_pop($result);
    }

    public function findTurmasWithoutAmbiente($ofertaId)
    {
        $turmasvinculadas = DB::table('int_ambientes_turmas')
           ->get();

        $turmasvinculadasId = [];

        foreach ($turmasvinculadas as $key => $value) {
            $turmasvinculadasId[] = $value->atr_trm_id;
        }

        $turmas = DB::table('acd_turmas')
           ->whereNotIn('trm_id', $turmasvinculadasId)
           ->where('trm_ofc_id', '=', $ofertaId)
           ->get();

        return $turmas;
    }

    public function findAmbientesWithMonitor()
    {
        $entries = DB::table('int_ambientes_virtuais')
                  ->join('int_ambientes_servicos', 'asr_amb_id', '=', 'amb_id')
                  ->join('int_servicos', 'asr_ser_id', '=', 'ser_id')
                  ->where('ser_nome', '=', 'MonitoramentoTempo')
                  ->get();

        return $entries;
    }

    public function findAmbienteWithMonitor($ambienteId)
    {
        $entries = DB::table('int_ambientes_virtuais')
                  ->join('int_ambientes_servicos', 'asr_amb_id', '=', 'amb_id')
                  ->join('int_servicos', 'asr_ser_id', '=', 'ser_id')
                  ->where('ser_nome', '=', 'MonitoramentoTempo')
                  ->where('amb_id', '=', $ambienteId)
                  ->first();

        return $entries;
    }
}
