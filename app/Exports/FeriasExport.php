<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class FeriasExport implements FromArray, WithColumnWidths
{
    protected $data;


    public function __construct(array $data)
    {
        $this->data = $data;
    }


    public function array(): array
    {
        return $this->data;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 40, // Nome do Colaborador
            'B' => 35, // Função
            'C' => 35, // Setor
            'D' => 18, // Data Contratação
            'E' => 18, // Início Período Gozo
            'F' => 18, // Fim Período Gozo
            'G' => 18, // Limite de Gozo
            'H' => 15, // Dias já Gozados
            'I' => 15, // Dias Vencidos
        ];
    }
}
