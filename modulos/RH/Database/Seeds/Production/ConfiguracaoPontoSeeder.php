<?php

namespace Modulos\RH\Database\Seeds\Production;

use Illuminate\Database\Seeder;
use Modulos\RH\Models\ConfiguracaoPonto;

class ConfiguracaoPontoSeeder extends Seeder
{
    public function run(): void
    {
        $configuracoes = [
            [
                'cop_chave' => 'ponto.janela_inicio',
                'cop_valor' => '05:00',
                'cop_descricao' => 'Horário inicial da janela válida para registros de ponto.',
            ],
            [
                'cop_chave' => 'ponto.janela_fim',
                'cop_valor' => '23:00',
                'cop_descricao' => 'Horário final da janela válida para registros de ponto.',
            ],
            [
                'cop_chave' => 'ponto.anti_duplicidade_seg',
                'cop_valor' => '60',
                'cop_descricao' => 'Intervalo mínimo em segundos para descartar eventos duplicados.',
            ],
            [
                'cop_chave' => 'ponto.retry_max_tentativas',
                'cop_valor' => '3',
                'cop_descricao' => 'Quantidade máxima de tentativas de reprocessamento.',
            ],
            [
                'cop_chave' => 'ponto.retry_delay_min',
                'cop_valor' => '5',
                'cop_descricao' => 'Atraso em minutos entre tentativas de reprocessamento.',
            ],
        ];

        foreach ($configuracoes as $configuracao) {
            ConfiguracaoPonto::updateOrCreate(
                ['cop_chave' => $configuracao['cop_chave']],
                $configuracao
            );
        }
    }
}
