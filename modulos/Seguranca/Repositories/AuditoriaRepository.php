<?php

namespace Modulos\Seguranca\Repositories;

use Illuminate\Support\Carbon;
use Modulos\Core\Repository\BaseRepository;
use Modulos\Seguranca\Models\Auditoria;

class AuditoriaRepository extends BaseRepository
{
    public function __construct(Auditoria $auditoria)
    {
        $this->model = $auditoria;
    }

    public function paginateRequest(array $requestData = [])
    {
        $query = $this->model->with('usuario.pessoa');

        // Filtros de Tabela, Ação, Registro...
        if (!empty($requestData['log_action'])) {
            $query->where('log_action', '=', $requestData['log_action']);
        }

        if (!empty($requestData['log_table'])) {
            $query->where('log_table', 'like', $requestData['log_table'] . '%'); // Prefira prefixo sem '%' no início para usar índice
        }

        if (!empty($requestData['log_table_id'])) {
            $query->where('log_table_id', '=', $requestData['log_table_id']);
        }

        // REGRA DE SEGURANÇA DE PERFORMANCE:
        // Se o usuário NÃO informou NENHUMA data, aplica limite dos últimos 30 dias por padrão
//        if (empty($requestData['data_inicio']) && empty($requestData['data_fim'])) {
//            $query->whereDate('created_at', '>=', Carbon::now()->subDays(30)->format('Y-m-d'));
//        } else {
        if (!empty($requestData['data_inicio'])) {
            $dataInicio = $this->formatDateToIso($requestData['data_inicio']);
            if ($dataInicio) {
                $query->whereDate('created_at', '>=', $dataInicio);
            }
        }

        if (!empty($requestData['data_fim'])) {
            $dataFim = $this->formatDateToIso($requestData['data_fim']);
            if ($dataFim) {
                $query->whereDate('created_at', '<=', $dataFim);
            }
        }
//        }

        return $query->orderBy('log_id', 'desc')->paginate(15);
    }

    /**
     * Converte datas no formato dd/mm/yyyy para Y-m-d para consulta no banco.
     */
    private function formatDateToIso($dateStr)
    {
        try {
            if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $dateStr)) {
                return Carbon::createFromFormat('d/m/Y', $dateStr)->format('Y-m-d');
            }

            return Carbon::parse($dateStr)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}