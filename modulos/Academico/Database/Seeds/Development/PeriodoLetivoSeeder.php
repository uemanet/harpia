<?php

namespace Modulos\Academico\Database\Seeds\Development;

use Illuminate\Database\Seeder;
use Modulos\Academico\Models\PeriodoLetivo;

class PeriodoLetivoSeeder extends Seeder
{
    public function run()
    {
        $periodo = new PeriodoLetivo();
        $periodo->per_nome = '2020.2';
        $periodo->per_inicio = '01/08/2020';
        $periodo->per_fim = '10/12/2020';
        $periodo->save();

        $periodo = new PeriodoLetivo();
        $periodo->per_nome = '2021.1';
        $periodo->per_inicio = '29/02/2021';
        $periodo->per_fim = '29/06/2021';
        $periodo->save();

        $periodo = new PeriodoLetivo();
        $periodo->per_nome = '2021.2';
        $periodo->per_inicio = '01/08/2021';
        $periodo->per_fim = '12/12/2021';
        $periodo->save();

        $periodo = new PeriodoLetivo();
        $periodo->per_nome = '2022.1';
        $periodo->per_inicio = '10/01/2022';
        $periodo->per_fim = '30/06/2022';
        $periodo->save();
    }
}
