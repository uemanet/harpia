<?php

namespace Modulos\RH\Services;

use Modulos\RH\Models\HoraTrabalhadaDiaria;
use Modulos\RH\Repositories\EventoAcessoRepository;

class HoraTrabalhadaDiariaService
{
    public function __construct(
        private EventoAcessoRepository $eventoRepository
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

        $horas = floor($totalSegundos / 3600);
        $minutos = floor(($totalSegundos % 3600) / 60);
        $segundos = $totalSegundos % 60;

        return sprintf('%02d:%02d:%02d', $horas, $minutos, $segundos);
    }
}
