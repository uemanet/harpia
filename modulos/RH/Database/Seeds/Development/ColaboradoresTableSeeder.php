<?php

namespace Modulos\RH\Database\Seeds\Development;

use Illuminate\Database\Seeder;
use DB;
use Modulos\RH\Models\Colaborador;


class ColaboradoresTableSeeder extends Seeder
{
    public function run()
    {
        // cadastra 30 colaboradores
        for ($i=1;$i<=30;$i++) {
            $colaborador = new Colaborador();

            $colaborador->col_pes_id = $i;
            $colaborador->col_qtd_filho = 1;
            $colaborador->col_ch_diaria = 8;
            $colaborador->col_codigo_catraca = $i;
            $colaborador->col_vinculo_universidade = 0;
            $colaborador->col_matricula_universidade = (string)$i;
            $colaborador->col_status = 'ativo';

            $colaborador->save();
        }
    }
}
