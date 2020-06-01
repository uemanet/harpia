<?php

namespace Modulos\Academico\Database\Seeds\Development;

use Illuminate\Database\Seeder;
use Modulos\Academico\Models\PeriodoLetivo;

class PeriodoLetivoSeeder extends Seeder
{
    public function run()
    {
        $periodo = new PeriodoLetivo();
        $periodo->per_nome = '2019.1';
        $periodo->per_inicio = '01/02/2019';
        $periodo->per_fim = '30/06/2019';
        $periodo->save();

        $periodo = new PeriodoLetivo();
        $periodo->per_nome = '2019.2';
        $periodo->per_inicio = '01/08/2019';
        $periodo->per_fim = '10/12/2019';
        $periodo->save();

        $periodo = new PeriodoLetivo();
        $periodo->per_nome = '2020.1';
        $periodo->per_inicio = '29/02/2020';
        $periodo->per_fim = '29/06/2020';
        $periodo->save();

        $periodo = new PeriodoLetivo();
        $periodo->per_nome = '2020.2';
        $periodo->per_inicio = '06/08/2020';
        $periodo->per_fim = '07/12/2020';
        $periodo->save();
    }
}
