<?php

namespace Modulos\RH\Services;

use Modulos\RH\Models\HoraTrabalhadaDiaria;
use Modulos\RH\Models\PeriodoLaboral;
use Modulos\RH\Repositories\EventoAcessoRepository;
use Modulos\RH\Repositories\HoraTrabalhadaRepository;

class HoraTrabalhadaDiariaService
{
    public function __construct(
        private EventoAcessoRepository $eventoRepository,
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
        $eventos = $this->eventoRepository->buscarEventosDoDia($colId, $data);

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
            $primeiroEventoSeguinte = $this->eventoRepository->buscarPrimeiroEventoDoDia($colId, $dataSeguinte);

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
