<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modulos\RH\Models\EventoAcesso;
use Modulos\RH\Services\HoraTrabalhadaDiariaService;

class ReconstruirHorasDiarias extends Command
{
    protected $signature = 'ponto:reconstruir-horas-diarias
                            {--col_id= : ID do colaborador especifico}
                            {--data= : Data especifica (Y-m-d)}
                            {--dias=30 : Processar apenas eventos dos ultimos N dias}';

    protected $description = 'Reconstroi as horas trabalhadas diarias a partir dos eventos de acesso ja processados.';

    public function __construct(
        private HoraTrabalhadaDiariaService $horaTrabalhadaDiariaService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $colId = $this->option('col_id') ? (int) $this->option('col_id') : null;
        $data = $this->option('data');
        $dias = (int) $this->option('dias');

        $query = EventoAcesso::whereIn('eva_status', ['processado', 'aprovado'])
            ->whereNotNull('eva_col_id')
            ->whereNotNull('eva_data_hora');

        if ($colId) {
            $query->where('eva_col_id', $colId);
        }

        if ($data) {
            $query->whereDate('eva_data_hora', $data);
        } elseif ($dias > 0) {
            $query->whereDate('eva_data_hora', '>=', date('Y-m-d', strtotime("-{$dias} days")));
        }

        $pares = $query
            ->selectRaw('eva_col_id, DATE(eva_data_hora) as data')
            ->groupBy('eva_col_id', 'data')
            ->orderBy('data')
            ->orderBy('eva_col_id')
            ->get();

        if ($pares->isEmpty()) {
            $this->info('Nenhum evento processado encontrado para reconstruir horas diarias.');

            return 0;
        }

        $this->info(sprintf('Encontrados %d pares (colaborador, data) para processar.', $pares->count()));

        $bar = $this->output->createProgressBar($pares->count());
        $erros = 0;

        foreach ($pares as $par) {
            try {
                $this->horaTrabalhadaDiariaService->atualizarOuCriar(
                    (int) $par->eva_col_id,
                    $par->data
                );
            } catch (\Throwable $e) {
                $this->warn(sprintf(
                    ' [ERRO] col_id=%d data=%s: %s',
                    $par->eva_col_id,
                    $par->data,
                    $e->getMessage()
                ));
                $erros++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info(sprintf(
            'Reconstrucao concluida: %d pares processados, %d erros.',
            $pares->count(),
            $erros
        ));

        return 0;
    }
}
