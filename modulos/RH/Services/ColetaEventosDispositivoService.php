<?php

namespace Modulos\RH\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Modulos\RH\Models\DispositivoAcesso;

class ColetaEventosDispositivoService
{
    public function __construct(
        private ControlIdApiClient $apiClient,
        private EventoAcessoService $eventoAcessoService
    ) {
    }

    public function coletarNovosEventos(DispositivoAcesso $dispositivo, ?int $limit = null): array
    {
        $limit = max(1, (int) ($limit ?: config('ponto.polling.limit', 500)));
        $ultimoIdAnterior = (int) ($dispositivo->dis_ultimo_access_log_id ?? 0);
        $ultimoIdAtual = $ultimoIdAnterior;
        $resultadoTotal = [
            'lidos' => 0,
            'lotes' => 0,
            'processado' => 0,
            'erro' => 0,
            'duplicado' => 0,
            'ignorado' => 0,
            'ultimo_id_anterior' => $ultimoIdAnterior,
            'ultimo_id_atual' => $ultimoIdAnterior,
        ];

        do {
            $logs = $this->extrairLogs($this->apiClient->loadObjects(
                $dispositivo,
                'access_logs',
                $this->montarPayload($ultimoIdAtual, $limit)
            ));

            if (empty($logs)) {
                break;
            }

            $resultadoLote = $this->eventoAcessoService->processarLoteAccessLogs($dispositivo, $logs, [
                'ip' => $dispositivo->dis_ip,
                'user_agent' => 'polling-controlid',
            ]);

            $resultadoTotal['lidos'] += count($logs);
            $resultadoTotal['lotes']++;
            $resultadoTotal['processado'] += $resultadoLote['processado'] ?? 0;
            $resultadoTotal['erro'] += $resultadoLote['erro'] ?? 0;
            $resultadoTotal['duplicado'] += $resultadoLote['duplicado'] ?? 0;
            $resultadoTotal['ignorado'] += $resultadoLote['ignorado'] ?? 0;
            $ultimoIdAtual = max($ultimoIdAtual, $this->resolverMaiorLogId($logs));
            $resultadoTotal['ultimo_id_atual'] = $ultimoIdAtual;
        } while (count($logs) === $limit);

        $dispositivo->forceFill([
            'dis_ultimo_access_log_id' => $ultimoIdAtual > 0 ? $ultimoIdAtual : null,
            'dis_ultima_coleta_em' => Carbon::now()->toDateTimeString(),
        ])->save();

        Log::channel('ponto')->info('Coleta por polling executada.', [
            'dispositivo_id' => $dispositivo->dis_id,
            'dispositivo_nome' => $dispositivo->dis_nome,
            'lidos' => $resultadoTotal['lidos'],
            'processado' => $resultadoTotal['processado'],
            'erro' => $resultadoTotal['erro'],
            'duplicado' => $resultadoTotal['duplicado'],
            'ignorado' => $resultadoTotal['ignorado'],
            'ultimo_id_anterior' => $resultadoTotal['ultimo_id_anterior'],
            'ultimo_id_atual' => $resultadoTotal['ultimo_id_atual'],
        ]);

        return $resultadoTotal;
    }

    private function montarPayload(int $ultimoId, int $limit): array
    {
        $payload = [
            'limit' => $limit,
            'order' => ['id', 'ascending'],
        ];

        if ($ultimoId > 0) {
            $payload['where'] = [
                [
                    'object' => 'access_logs',
                    'field' => 'id',
                    'operator' => '>',
                    'value' => $ultimoId,
                ],
            ];
        }

        return $payload;
    }

    private function extrairLogs(array $resposta): array
    {
        foreach (['access_logs', 'values', 'objects'] as $key) {
            if (isset($resposta[$key]) && is_array($resposta[$key])) {
                return array_values(array_filter($resposta[$key], 'is_array'));
            }
        }

        if (count($resposta) === 1) {
            $primeiroValor = reset($resposta);
            if (is_array($primeiroValor)) {
                return array_values(array_filter($primeiroValor, 'is_array'));
            }
        }

        return array_values(array_filter($resposta, 'is_array'));
    }

    private function resolverMaiorLogId(array $logs): int
    {
        $ids = array_values(array_filter(array_map(function (array $log) {
            $id = (int) ($log['id'] ?? 0);
            return $id > 0 ? $id : null;
        }, $logs)));

        return empty($ids) ? 0 : max($ids);
    }
}
