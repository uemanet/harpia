<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Modulos\RH\Models\HoraTrabalhadaDiaria;

class HoraTrabalhadaDiariaImport implements ToModel
{
    use Importable;

    public function model(array $row)
    {
        return new HoraTrabalhadaDiaria([
            'col_codigo_catraca' => $row[0],
            'htd_horas' => $row[1],
            'htd_data' => $row[2],
        ]);
    }
}