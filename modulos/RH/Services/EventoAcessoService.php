<?php

namespace Modulos\RH\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Modulos\RH\Models\DispositivoAcesso;
use Modulos\RH\Models\EventoAcesso;

class EventoAcessoService
{
    private const EVENTOS_SUPORTADOS = [0, 1, 2, 3, 4, 5, 6, 7, 12];

    public function __construct(
        private AntiDuplicidadeService $antiDuplicidadeService,
        private MatchingService $matchingService
    ) {
    }

    public function processarEventoMonitor(DispositivoAcesso $dispositivo, array $evento, array $contexto = []): array
    {
        $userId = (string) ($evento['user_id'] ?? '');
        $deviceId = (string) ($evento['device_id'] ?? $dispositivo->dis_identificador);
        $timestamp = $this->resolverDataHora($evento['time'] ?? null);
        $eventCode = (int) ($evento['event'] ?? -1);

        if (!in_array($eventCode, self::EVENTOS_SUPORTADOS, true)) {
            return [
                'status' => 'ignorado',
                'motivo' => 'Codigo de evento nao suportado para registro de acesso.',
            ];
        }

        $hash = $this->antiDuplicidadeService->gerarHash(
            $userId !== '' ? $userId : (string) ($evento['id'] ?? 'desconhecido'),
            $deviceId,
            $timestamp->toDateTimeString()
        );

        if ($this->antiDuplicidadeService->existeHash($hash)) {
            return ['status' => 'duplicado', 'hash' => $hash];
        }

        $colaborador = $userId !== ''
            ? $this->matchingService->resolver($userId, $dispositivo->dis_id)
            : null;

        $status = 'processado';
        $mensagem = $this->descricaoEvento($eventCode);

        if (!$colaborador) {
            $status = 'erro';
            $mensagem = 'Usuario nao vinculado no Harpia.';
        } elseif ($eventCode === 12) {
            $status = 'erro';
            $mensagem = 'Acesso nao autorizado pelo dispositivo.';
        }

        $registro = EventoAcesso::create([
            'eva_col_id' => $colaborador?->col_id,
            'eva_dis_id' => $dispositivo->dis_id,
            'eva_tipo' => $dispositivo->dis_tipo,
            'eva_data_hora' => $timestamp->toDateTimeString(),
            'eva_origem' => 'idface',
            'eva_status' => $status,
            'eva_status_mensagem' => $mensagem,
            'eva_hash' => $hash,
            'eva_ip_origem' => $contexto['ip'] ?? null,
            'eva_user_agent' => $contexto['user_agent'] ?? null,
            'eva_observacao' => $this->montarObservacao($evento, $eventCode),
        ]);

        return [
            'status' => $status,
            'registro' => $registro,
            'hash' => $hash,
        ];
    }

    public function processarLoteAccessLogs(DispositivoAcesso $dispositivo, array $logs, array $contexto = []): array
    {
        $resultado = [
            'processado' => 0,
            'erro' => 0,
            'duplicado' => 0,
            'ignorado' => 0,
        ];

        foreach ($logs as $log) {
            $processamento = $this->processarEventoMonitor($dispositivo, $log, $contexto);
            $status = $processamento['status'];

            if (!array_key_exists($status, $resultado)) {
                $status = 'ignorado';
            }

            $resultado[$status]++;
        }

        return $resultado;
    }

    public function reprocessarEventoComFalha(EventoAcesso $evento): bool
    {
        if ($evento->eva_status !== 'erro' || !$evento->eva_dis_id) {
            return false;
        }

        $observacao = json_decode((string) $evento->eva_observacao, true);

        if (!is_array($observacao)) {
            return false;
        }

        $eventCode = (int) ($observacao['event_code'] ?? -1);

        if ($eventCode === 12) {
            return false;
        }

        $userId = (string) ($observacao['user_id'] ?? '');

        if ($userId === '') {
            return false;
        }

        $colaborador = $this->matchingService->resolver($userId, (int) $evento->eva_dis_id);

        if (!$colaborador) {
            $evento->fill([
                'eva_status_mensagem' => 'Usuario ainda nao vinculado no Harpia.',
            ])->save();

            return false;
        }

        $mensagem = $this->descricaoEvento($eventCode);

        $evento->fill([
            'eva_col_id' => $colaborador->col_id,
            'eva_status' => 'processado',
            'eva_status_mensagem' => $mensagem,
        ])->save();

        return true;
    }

    private function resolverDataHora(mixed $valor): Carbon
    {
        if (is_numeric($valor)) {
            return Carbon::createFromTimestamp((int) $valor);
        }

        return Carbon::parse((string) $valor);
    }

    private function descricaoEvento(int $eventCode): string
    {
        return match ($eventCode) {
            0 => 'Identificacao por senha.',
            1 => 'Identificacao por cartao.',
            2 => 'Identificacao por biometria.',
            3 => 'Identificacao por cartao e senha.',
            4 => 'Identificacao por cartao e biometria.',
            5 => 'Identificacao por biometria e senha.',
            6 => 'Identificacao por cartao, biometria e senha.',
            7 => 'Reconhecimento facial.',
            12 => 'Acesso negado.',
            default => 'Evento de acesso recebido.',
        };
    }

    private function montarObservacao(array $evento, int $eventCode): string
    {
        $observacao = [
            'event_code' => $eventCode,
            'event_description' => $this->descricaoEvento($eventCode),
            'user_id' => $evento['user_id'] ?? null,
            'device_id' => $evento['device_id'] ?? null,
            'time' => $evento['time'] ?? null,
        ];

        foreach (['id', 'identifier_id', 'portal_id', 'identification_rule_id', 'card_value', 'log_type_id'] as $campo) {
            if (array_key_exists($campo, $evento)) {
                $observacao[$campo] = $evento[$campo];
            }
        }

        return json_encode($observacao, JSON_UNESCAPED_UNICODE);
    }
}
