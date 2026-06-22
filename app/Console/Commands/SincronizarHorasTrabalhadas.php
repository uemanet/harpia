<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modulos\RH\Models\PeriodoLaboral;
use Modulos\RH\Repositories\HoraTrabalhadaRepository;

class SincronizarHorasTrabalhadas extends Command
{
    protected $signature = 'ponto:sincronizar-horas-trabalhadas {pel_id? : ID do periodo laboral especifico}';

    protected $description = 'Sincroniza a tabela agregada de horas trabalhadas (reh_horas_trabalhadas) a partir das horas diarias.';

    public function __construct(
        private HoraTrabalhadaRepository $horaTrabalhadaRepository
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $periodoId = $this->argument('pel_id');

        if ($periodoId) {
            $periodo = PeriodoLaboral::find((int) $periodoId);

            if (!$periodo) {
                $this->error('Periodo laboral nao encontrado.');

                return 1;
            }

            $this->info(sprintf(
                'Sincronizando horas trabalhadas para o periodo: %s a %s...',
                $periodo->getRawOriginal('pel_inicio'),
                $periodo->getRawOriginal('pel_termino')
            ));

            $this->horaTrabalhadaRepository->sincronizarHorasTrabalhadas($periodo);

            $this->info('Sincronizacao concluida.');

            return 0;
        }

        $periodos = PeriodoLaboral::where('pel_encerramento', null)
            ->where('pel_termino', '>=', date('Y-m-d'))
            ->orderBy('pel_inicio', 'desc')
            ->get();

        if ($periodos->isEmpty()) {
            $this->warn('Nenhum periodo laboral ativo encontrado.');

            return 0;
        }

        $this->info(sprintf('Sincronizando %d periodo(s) laboral(is)...', $periodos->count()));

        $bar = $this->output->createProgressBar($periodos->count());

        foreach ($periodos as $periodo) {
            $this->horaTrabalhadaRepository->sincronizarHorasTrabalhadas($periodo);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Sincronizacao de todos os periodos ativos concluida.');

        return 0;
    }
}
