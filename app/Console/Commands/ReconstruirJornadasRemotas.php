<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modulos\RH\Models\EventoAcesso;
use Modulos\RH\Models\JornadaRemota;
use Modulos\RH\Repositories\JornadaRemotaRepository;
use Modulos\RH\Services\HoraTrabalhadaDiariaService;

class ReconstruirJornadasRemotas extends Command
{
    protected $signature = 'ponto:reconstruir-jornadas-remotas
                            {--col_id= : ID do colaborador especifico}
                            {--data= : Data especifica (Y-m-d)}';

    protected $description = 'Reconstrói jornadas remotas agrupadas a partir de eventos home office já existentes.';

    public function __construct(
        private JornadaRemotaRepository $jornadaRemotaRepository,
        private HoraTrabalhadaDiariaService $horaTrabalhadaDiariaService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $query = EventoAcesso::with(['jornada_entrada', 'jornada_saida'])
            ->where('eva_origem', 'home_office')
            ->whereIn('eva_status', ['pendente', 'aprovado', 'reprovado'])
            ->whereNotNull('eva_col_id')
            ->orderBy('eva_col_id')
            ->orderBy('eva_data_hora');

        if ($this->option('col_id')) {
            $query->where('eva_col_id', (int) $this->option('col_id'));
        }

        if ($this->option('data')) {
            $query->whereDate('eva_data_hora', $this->option('data'));
        }

        $eventos = $query->get();

        if ($eventos->isEmpty()) {
            $this->info('Nenhum evento remoto encontrado para reconstruir jornadas.');

            return 0;
        }

        $abertas = [];
        $afetados = [];
        $criadas = 0;
        $fechadas = 0;
        $inconsistentes = 0;
        $ignoradas = 0;

        foreach ($eventos as $evento) {
            if ($evento->jornada_entrada || $evento->jornada_saida) {
                $ignoradas++;
                continue;
            }

            $colId = (int) $evento->eva_col_id;
            $chave = (string) $colId;

            if ($evento->eva_tipo === 'entrada') {
                if (isset($abertas[$chave])) {
                    $abertas[$chave]['jornada']->fill([
                        'jor_status' => 'inconsistente',
                        'jor_motivo_aprovacao' => 'Entrada remota anterior sem saida pareavel durante a reconstrucao.',
                    ])->save();
                    $inconsistentes++;
                }

                $abertas[$chave] = [
                    'evento' => $evento,
                    'jornada' => $this->jornadaRemotaRepository->abrirJornada($colId, $evento),
                ];
                $criadas++;

                continue;
            }

            $atividades = $this->extrairAtividadesDoEvento($evento);

            if (!isset($abertas[$chave])) {
                $this->jornadaRemotaRepository->criarJornadaInconsistenteDeSaida($colId, $evento, $atividades);
                $inconsistentes++;
                $afetados[$colId . '|' . date('Y-m-d', strtotime($evento->eva_data_hora))] = [
                    'col_id' => $colId,
                    'data' => date('Y-m-d', strtotime($evento->eva_data_hora)),
                ];
                continue;
            }

            $aberta = $abertas[$chave];
            $jornada = $this->jornadaRemotaRepository->fecharJornadaComSaida(
                $aberta['jornada'],
                $evento,
                $atividades
            );

            $this->aplicarStatusReconstruido($jornada, $aberta['evento'], $evento);

            $afetados[$colId . '|' . $jornada->jor_data_referencia] = [
                'col_id' => $colId,
                'data' => $jornada->jor_data_referencia,
            ];
            $fechadas++;
            unset($abertas[$chave]);
        }

        foreach ($abertas as $aberta) {
            $aberta['jornada']->fill([
                'jor_status' => 'inconsistente',
                'jor_motivo_aprovacao' => 'Entrada remota sem saida pareavel ao final da reconstrucao.',
            ])->save();
            $inconsistentes++;
        }

        foreach ($afetados as $afetado) {
            $this->horaTrabalhadaDiariaService->atualizarOuCriar($afetado['col_id'], $afetado['data']);
        }

        $this->info(sprintf(
            'Reconstrucao concluida: %d jornadas abertas, %d fechadas, %d inconsistentes, %d eventos ignorados.',
            $criadas,
            $fechadas,
            $inconsistentes,
            $ignoradas
        ));

        return 0;
    }

    private function aplicarStatusReconstruido(JornadaRemota $jornada, EventoAcesso $entrada, EventoAcesso $saida): void
    {
        if ($entrada->eva_status === 'reprovado' || $saida->eva_status === 'reprovado') {
            $jornada->fill([
                'jor_status' => 'reprovado',
                'jor_horas_aprovadas' => '00:00:00',
                'jor_motivo_aprovacao' => 'Jornada reconstruida a partir de eventos remotos reprovados.',
            ])->save();

            return;
        }

        if ($entrada->eva_status === 'aprovado' && $saida->eva_status === 'aprovado') {
            $jornada->fill([
                'jor_status' => 'aprovado',
                'jor_horas_aprovadas' => $jornada->jor_horas_calculadas ?: '00:00:00',
                'jor_motivo_aprovacao' => 'Jornada reconstruida a partir de eventos remotos já aprovados.',
            ])->save();

            return;
        }

        $jornada->fill([
            'jor_status' => 'pendente',
            'jor_horas_aprovadas' => null,
            'jor_motivo_aprovacao' => null,
        ])->save();
    }

    private function extrairAtividadesDoEvento(EventoAcesso $evento): string
    {
        $observacao = json_decode((string) $evento->eva_observacao, true);

        if (!is_array($observacao)) {
            return '';
        }

        return trim((string) ($observacao['atividades'] ?? $observacao['observacao_usuario'] ?? ''));
    }
}
