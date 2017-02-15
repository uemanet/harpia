<?php

namespace Modulos\Academico\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Academico\Models\Matricula;

class MatriculaCursoTableSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 50; $i++) {
            $matricula = new Matricula();

            $matricula->mat_alu_id = $i;
            $matricula->mat_trm_id = 1;
            $matricula->mat_pol_id = 1;
            $matricula->mat_grp_id = null;
            $matricula->mat_situacao = 'cursando';
            $matricula->mat_modo_entrada = 'vestibular';

            $matricula->save();
        }

        for ($i = 51; $i <= 100; $i++) {
            $matricula = new Matricula();

            $matricula->mat_alu_id = $i;
            $matricula->mat_trm_id = 2;
            $matricula->mat_pol_id = 1;
            $matricula->mat_grp_id = null;
            $matricula->mat_situacao = 'cursando';
            $matricula->mat_modo_entrada = 'vestibular';

            $matricula->save();
        }

        for ($i = 101; $i <= 150; $i++) {
            $matricula = new Matricula();

            $matricula->mat_alu_id = $i;
            $matricula->mat_trm_id = 3;
            $matricula->mat_pol_id = 4;
            $matricula->mat_grp_id = null;
            $matricula->mat_situacao = 'cursando';
            $matricula->mat_modo_entrada = 'vestibular';

            $matricula->save();
        }
    }
}