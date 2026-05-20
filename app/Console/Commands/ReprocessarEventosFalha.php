<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modulos\RH\Repositories\EventoAcessoRepository;
use Modulos\RH\Services\EventoAcessoService;
use Modulos\RH\Services\HoraTrabalhadaDiariaService;

class ReprocessarEventosFalha extends Command
{
    protected $signature = 'ponto:reprocessar-eventos-falha {--limit=100}';

    protected $description = 'Reprocessa eventos de acesso que falharam por causa transitoria';

    public function __construct(
        private EventoAcessoRepository $eventoRepository,
        private EventoAcessoService $eventoAcessoService,
        private HoraTrabalhadaDiariaService $horaTrabalhadaDiariaService
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

                if ($evento->eva_col_id && $evento->eva_data_hora) {
                    $this->horaTrabalhadaDiariaService->atualizarOuCriar(
                        $evento->eva_col_id,
                        date('Y-m-d', strtotime($evento->eva_data_hora))
                    );
                }

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
