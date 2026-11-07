<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ColaboradoresExport implements FromArray, WithColumnWidths
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
            'A' => 45, // Nome
            'B' => 40, // Email
            'C' => 20, // CPF
            'D' => 35, // Setor
            'E' => 35, // Função
            'F' => 15, // Status
            'G' => 15, // Foto
        ];
    }
}