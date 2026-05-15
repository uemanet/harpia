<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modulos\RH\Repositories\EventoAcessoRepository;
use Modulos\RH\Services\EventoAcessoService;

class ReprocessarEventosFalha extends Command
{
    protected $signature = 'ponto:reprocessar-eventos-falha {--limit=100}';

    protected $description = 'Reprocessa eventos de acesso que falharam por causa transitoria';

    public function __construct(
        private EventoAcessoRepository $eventoRepository,
        private EventoAcessoService $eventoAcessoService
    ) {
        parent::__construct();
    }

    public function handle()
    {
        $limite = (int) $this->option('limit');
        $eventos = $this->eventoRepository->buscarEventosComErro($limite);
        $reprocessados = 0;
        $mantidos = 0;

        foreach ($eventos as $evento) {
            if ($this->eventoAcessoService->reprocessarEventoComFalha($evento)) {
                $reprocessados++;
                continue;
            }

            $mantidos++;
        }

        $this->info(sprintf(
            'Reprocessamento concluido: %d reprocessados, %d mantidos em erro.',
            $reprocessados,
            $mantidos
        ));

        return 0;
    }
}
