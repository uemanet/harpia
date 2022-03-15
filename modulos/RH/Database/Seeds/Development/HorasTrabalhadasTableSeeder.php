<?php

namespace Modulos\RH\Database\Seeds\Development;

use Illuminate\Database\Seeder;

use DB;
use Modulos\RH\Models\HoraTrabalhada;


class HorasTrabalhadasTableSeeder extends Seeder
{
    public function run()
    {
        // cadastra 30 colaboradores
        for ($i=1;$i<=30;$i++) {
            $horaTrabalhada = new HoraTrabalhada();

            $horaTrabalhada->htr_col_id = $i;
            $horaTrabalhada->htr_pel_id = 1;
            $horaTrabalhada->htr_horas_previstas = '160:00:00';
            $horaTrabalhada->htr_horas_trabalhadas = '160:00:00';
            $horaTrabalhada->htr_horas_justificadas = '160:00:00';
            $horaTrabalhada->htr_saldo = '00:00:00';

            $horaTrabalhada->save();
        }
    }
}
