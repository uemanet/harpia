<?php

namespace Modulos\RH\Services;

use Modulos\RH\Models\HoraTrabalhadaDiaria;
use Modulos\RH\Models\PeriodoLaboral;
use Modulos\RH\Repositories\EventoAcessoRepository;
use Modulos\RH\Repositories\HoraTrabalhadaRepository;
use Modulos\RH\Repositories\JornadaRemotaRepository;

class HoraTrabalhadaDiariaService
{
    public function __construct(
        private EventoAcessoRepository $eventoRepository,
        private JornadaRemotaRepository $jornadaRemotaRepository,
        private HoraTrabalhadaRepository $horaTrabalhadaRepository
    ) {
    }

    public function atualizarOuCriar(int $colId, string $data): void
    {
        $horas = $this->calcularHorasDoDia($colId, $data);

        HoraTrabalhadaDiaria::updateOrCreate(
            [
                'htd_col_id' => $colId,
                'htd_data' => $data,
            ],
            [
                'htd_horas' => $horas,
            ]
        );

        $this->sincronizarPeriodoAgregado($colId, $data);
    }

    public function calcularHorasDoDia(int $colId, string $data): string
    {
        $horasIdface = $this->calcularHorasPorEventos($colId, $data, 'idface', ['processado', 'aprovado']);
        $horasRemotasAgrupadas = $this->calcularHorasRemotasAgrupadas($colId, $data);
        $eventosVinculados = $this->jornadaRemotaRepository->listarIdsEventosVinculadosPorDia($colId, $data);
        $horasRemotasLegadas = $this->calcularHorasPorEventos(
            $colId,
            $data,
            'home_office',
            ['aprovado'],
            $eventosVinculados
        );

        return $this->somarTempos([
            $horasIdface,
            $horasRemotasAgrupadas,
            $horasRemotasLegadas,
        ]);
    }

    private function calcularHorasPorEventos(
        int $colId,
        string $data,
        string $origem,
        array $statuses,
        array $ignorarEvaIds = []
    ): string {
        $eventos = $this->eventoRepository->buscarEventosDoDia($colId, $data, $origem, $statuses, $ignorarEvaIds);
        $totalSegundos = 0;
        $entrada = null;

        foreach ($eventos as $evento) {
            if ($evento['eva_tipo'] === 'entrada') {
                $entrada = strtotime($evento['eva_data_hora']);
            } elseif ($evento['eva_tipo'] === 'saida' && $entrada !== null) {
                $saida = strtotime($evento['eva_data_hora']);
                $totalSegundos += $saida - $entrada;
                $entrada = null;
            }
        }

        // Turno noturno: entrada hoje, saida amanha
        if ($entrada !== null) {
            $dataSeguinte = date('Y-m-d', strtotime($data . ' +1 day'));
            $primeiroEventoSeguinte = $this->eventoRepository->buscarPrimeiroEventoDoDia(
                $colId,
                $dataSeguinte,
                $origem,
                $statuses,
                $ignorarEvaIds
            );

            if ($primeiroEventoSeguinte && $primeiroEventoSeguinte->eva_tipo === 'saida') {
                $saida = strtotime($primeiroEventoSeguinte->eva_data_hora);
                $totalSegundos += $saida - $entrada;
            }
        }

        $horas = floor($totalSegundos / 3600);
        $minutos = floor(($totalSegundos % 3600) / 60);
        $segundos = $totalSegundos % 60;

        return sprintf('%02d:%02d:%02d', $horas, $minutos, $segundos);
    }

    private function calcularHorasRemotasAgrupadas(int $colId, string $data): string
    {
        $tempos = $this->jornadaRemotaRepository
            ->buscarJornadasComHorasNoDia($colId, $data)
            ->map(function ($jornada) {
                if ($jornada->jor_status === 'parcial') {
                    return $jornada->jor_horas_aprovadas ?: '00:00:00';
                }

                return $jornada->jor_horas_calculadas ?: '00:00:00';
            })
            ->all();

        return $this->somarTempos($tempos);
    }

    private function somarTempos(array $tempos): string
    {
        $totalSegundos = 0;

        foreach ($tempos as $tempo) {
            $valor = (string) $tempo;

            if ($valor === '') {
                continue;
            }

            $partes = explode(':', $valor);

            if (count($partes) !== 3) {
                continue;
            }

            $totalSegundos += (((int) $partes[0]) * 3600) + (((int) $partes[1]) * 60) + ((int) $partes[2]);
        }

        $horas = floor($totalSegundos / 3600);
        $minutos = floor(($totalSegundos % 3600) / 60);
        $segundos = $totalSegundos % 60;

        return sprintf('%02d:%02d:%02d', $horas, $minutos, $segundos);
    }

    private function sincronizarPeriodoAgregado(int $colId, string $data): void
    {
        $periodos = PeriodoLaboral::where('pel_inicio', '<=', $data)
            ->where('pel_termino', '>=', $data)
            ->get();

        foreach ($periodos as $periodo) {
            $this->horaTrabalhadaRepository->sincronizarHorasTrabalhadasDoColaborador($periodo, $colId);
        }
    }
}
