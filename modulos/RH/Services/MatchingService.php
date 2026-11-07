<?php

namespace Modulos\RH\Services;

use Illuminate\Support\Facades\Cache;
use Modulos\RH\Models\Colaborador;
use Modulos\RH\Models\MapeamentoDispositivo;

class MatchingService
{
    public function resolver(string $userId, int $dispositivoId): ?Colaborador
    {
        $detalhes = $this->resolverDetalhes($userId, $dispositivoId);

        if (!$detalhes || empty($detalhes['col_id'])) {
            return null;
        }

        return Colaborador::with('pessoa')
            ->where('col_id', $detalhes['col_id'])
            ->where('col_status', 'ativo')
            ->first();
    }

    public function resolverRegistration(string $userId, int $dispositivoId): ?string
    {
        $detalhes = $this->resolverDetalhes($userId, $dispositivoId);
        return $detalhes['registration'] ?? null;
    }

    public function invalidar(string $userId, int $dispositivoId): void
    {
        Cache::forget($this->cacheKey($userId, $dispositivoId));
    }

    private function resolverDetalhes(string $userId, int $dispositivoId): ?array
    {
        return Cache::remember($this->cacheKey($userId, $dispositivoId), 300, function () use ($userId, $dispositivoId) {
            $mapeamento = MapeamentoDispositivo::where('map_dis_id', $dispositivoId)
                ->where('map_user_id', $userId)
                ->where('map_ativo', true)
                ->first();

            if (!$mapeamento) {
                return null;
            }

            return [
                'col_id' => $mapeamento->map_col_id,
                'registration' => $mapeamento->map_registration,
            ];
        });
    }

    private function cacheKey(string $userId, int $dispositivoId): string
    {
        return sprintf('matching:%d:%s', $dispositivoId, $userId);
    }
}
