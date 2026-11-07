<?php

namespace Modulos\RH\Repositories;

use Illuminate\Support\Collection;
use Modulos\Core\Repository\BaseRepository;
use Modulos\RH\Models\EventoAcesso;
use Modulos\RH\Models\JornadaRemota;

class JornadaRemotaRepository extends BaseRepository
{
    public function __construct(JornadaRemota $jornadaRemota)
    {
        $this->model = $jornadaRemota;
    }

    public function buscarAbertaDoColaborador(int $colId): ?JornadaRemota
    {
        return $this->model
            ->where('jor_col_id', $colId)
            ->where('jor_status', 'aberta')
            ->orderBy('jor_entrada_em', 'desc')
            ->first();
    }

    public function marcarAbertasComoInconsistentes(int $colId, ?string $motivo = null): int
    {
        return $this->model
            ->where('jor_col_id', $colId)
            ->where('jor_status', 'aberta')
            ->update([
                'jor_status' => 'inconsistente',
                'jor_motivo_aprovacao' => $motivo,
            ]);
    }

    public function abrirJornada(int $colId, EventoAcesso $eventoEntrada): JornadaRemota
    {
        return $this->model->create([
            'jor_col_id' => $colId,
            'jor_eva_entrada_id' => $eventoEntrada->eva_id,
            'jor_data_referencia' => date('Y-m-d', strtotime($eventoEntrada->eva_data_hora)),
            'jor_entrada_em' => $eventoEntrada->eva_data_hora,
            'jor_status' => 'aberta',
        ]);
    }

    public function fecharJornadaComSaida(JornadaRemota $jornada, EventoAcesso $eventoSaida, string $atividades): JornadaRemota
    {
        $horasCalculadas = $this->calcularDuracao(
            (string) $jornada->jor_entrada_em,
            (string) $eventoSaida->eva_data_hora
        );

        $jornada->fill([
            'jor_eva_saida_id' => $eventoSaida->eva_id,
            'jor_saida_em' => $eventoSaida->eva_data_hora,
            'jor_atividades' => $atividades,
            'jor_horas_calculadas' => $horasCalculadas,
            'jor_horas_aprovadas' => null,
            'jor_status' => 'pendente',
            'jor_motivo_aprovacao' => null,
            'jor_aprovador_col_id' => null,
            'jor_aprovado_em' => null,
        ])->save();

        return $jornada->fresh();
    }

    public function criarJornadaInconsistenteDeSaida(int $colId, EventoAcesso $eventoSaida, string $atividades): JornadaRemota
    {
        return $this->model->create([
            'jor_col_id' => $colId,
            'jor_eva_saida_id' => $eventoSaida->eva_id,
            'jor_data_referencia' => date('Y-m-d', strtotime($eventoSaida->eva_data_hora)),
            'jor_saida_em' => $eventoSaida->eva_data_hora,
            'jor_atividades' => $atividades,
            'jor_horas_calculadas' => '00:00:00',
            'jor_horas_aprovadas' => '00:00:00',
            'jor_status' => 'inconsistente',
            'jor_motivo_aprovacao' => 'Saida remota sem entrada pareavel.',
        ]);
    }

    public function buscarJornadasComHorasNoDia(int $colId, string $data): Collection
    {
        return $this->model
            ->where('jor_col_id', $colId)
            ->where('jor_data_referencia', $data)
            ->whereIn('jor_status', ['aprovado', 'parcial'])
            ->orderBy('jor_entrada_em')
            ->get();
    }

    public function listarIdsEventosVinculadosPorDia(int $colId, string $data): array
    {
        return $this->model
            ->where('jor_col_id', $colId)
            ->where('jor_data_referencia', $data)
            ->get(['jor_eva_entrada_id', 'jor_eva_saida_id'])
            ->flatMap(function (JornadaRemota $jornada) {
                return [$jornada->jor_eva_entrada_id, $jornada->jor_eva_saida_id];
            })
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    public function calcularDuracao(string $inicio, string $fim): string
    {
        $inicioTimestamp = strtotime($inicio);
        $fimTimestamp = strtotime($fim);
        $totalSegundos = max(0, $fimTimestamp - $inicioTimestamp);

        $horas = floor($totalSegundos / 3600);
        $minutos = floor(($totalSegundos % 3600) / 60);
        $segundos = $totalSegundos % 60;

        return sprintf('%02d:%02d:%02d', $horas, $minutos, $segundos);
    }
}
