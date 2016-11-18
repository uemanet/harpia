<?php

namespace Modulos\Integracao\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Integracao\Models\Servico;
use DB;

class ServicoRepository extends BaseRepository
{
    public function __construct(Servico $servico)
    {
        $this->model = $servico;
    }

    public function listsServicosWithoutAmbiente($ambienteId)
    {
        $entries = DB::table('int_ambientes_servicos')
                    ->join('int_ambientes_virtuais', 'asr_amb_id', '=', 'amb_id')
                    ->where('asr_amb_id', '=', $ambienteId)
                    ->get();

        $servicosId = [];
        foreach ($entries as $key => $value) {
            $servicosId[] = $value->asr_ser_id;
        }

        $result = $this->model
            ->whereNotIn('ser_id', $servicosId)
            ->pluck('ser_nome', 'ser_id');

        return $result;
    }

    public function verifyIfExistsAmbienteServico($ambienteId, $servicoId)
    {
        $exists = DB::table('int_ambientes_servicos')
                  ->where('asr_amb_id', '=', $ambienteId)
                  ->where('asr_ser_id', '=', $servicoId)
                  ->first();
                  
        if ($exists) {
            return true;
        }

        return false;
    }
}
