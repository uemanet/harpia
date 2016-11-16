<?php

namespace Modulos\Academico\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Academico\Models\PeriodoLetivo;

class PeriodoLetivoSeeder extends Seeder
{
    public function run()
    {
        $periodo = new PeriodoLetivo();
        $periodo->per_nome = '2016.2';
        $periodo->per_inicio = '01/08/2016';
        $periodo->per_fim = '10/12/2016';
        $periodo->save();

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
    }

}