<?php

namespace Modulos\Academico\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Academico\Models\PeriodoLetivo;

class PeriodoLetivoSeeder extends Seeder
{
    public function run()
    {
        $periodo = new PeriodoLetivo();
        $periodo->per_nome = '2017.1';
        $periodo->per_inicio = '01/02/2017';
        $periodo->per_fim = '30/06/2017';
        $periodo->save();

        $periodo = new PeriodoLetivo();
        $periodo->per_nome = '2017.2';
        $periodo->per_inicio = '01/08/2017';
        $periodo->per_fim = '10/12/2017';
        $periodo->save();

        $periodo = new PeriodoLetivo();
        $periodo->per_nome = '2018.1';
        $periodo->per_inicio = '29/02/2018';
        $periodo->per_fim = '29/06/2018';
        $periodo->save();

        $periodo = new PeriodoLetivo();
        $periodo->per_nome = '2018.2';
        $periodo->per_inicio = '06/08/2018';
        $periodo->per_fim = '07/12/2018';
        $periodo->save();
    }
}
