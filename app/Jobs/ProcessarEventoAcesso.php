<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modulos\RH\Models\DispositivoAcesso;
use Modulos\RH\Services\EventoAcessoService;
use Modulos\RH\Services\HoraTrabalhadaDiariaService;

class ProcessarEventoAcesso implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private int $dispositivoId,
        private array $evento,
        private array $contexto = []
    ) {
    }

    public function handle(EventoAcessoService $eventoAcessoService, HoraTrabalhadaDiariaService $horaService): void
    {
        $dispositivo = DispositivoAcesso::find($this->dispositivoId);

        if (!$dispositivo) {
            return;
        }

        $resultado = $eventoAcessoService->processarEventoMonitor($dispositivo, $this->evento, $this->contexto);

        if ($resultado['status'] === 'processado' && isset($resultado['registro'])) {
            $registro = $resultado['registro'];

            if ($registro->eva_col_id) {
                $horaService->atualizarOuCriar(
                    $registro->eva_col_id,
                    date('Y-m-d', strtotime($registro->eva_data_hora))
                );
            }
        }
    }
}
