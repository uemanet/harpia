<?php
namespace Modulos\RH\Database\Seeds\Development;

use Illuminate\Database\Seeder;
use Modulos\RH\Models\FontePagadora;
use Modulos\RH\Models\Funcao;
use Modulos\RH\Models\PeriodoLaboral;

class PeriodosLaboraisTableSeeder extends Seeder
{
    public function run()
    {

        $periodo_laboral = new PeriodoLaboral();

        $periodo_laboral->pel_inicio = '01/03/2022';
        $periodo_laboral->pel_inicio = '31/03/2022';

        $periodo_laboral->save();




    }
}
