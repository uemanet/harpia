<?php

namespace Modulos\RH\Services;

use Carbon\Carbon;
use Modulos\RH\Models\EventoAcesso;

class AntiDuplicidadeService
{
    public function gerarHash(string $identificador, string $dispositivoId, string $dataHora): string
    {
        $minuto = Carbon::parse($dataHora)->format('Y-m-d H:i:00');
        $payload = sprintf('%s|%s|%s', $identificador, $dispositivoId, $minuto);

        return hash('sha256', $payload);
    }

    public function existeHash(string $hash): bool
    {
        return EventoAcesso::where('eva_hash', $hash)->exists();
    }
}
