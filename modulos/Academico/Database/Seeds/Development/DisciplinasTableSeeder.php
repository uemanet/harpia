<?php

namespace Modulos\Academico\Database\Seeds\Development;

use Illuminate\Database\Seeder;
use Modulos\Academico\Models\Disciplina;

class DisciplinasTableSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('pt_BR');

        // criar 30 disciplinas de cada modalidade de curso

        for ($j=1;$j<=5;$j++) {
            for ($i=0;$i<30;$i++) {
                $disciplina = new Disciplina();

                $disciplina->dis_nome = $faker->sentence(2);
                $disciplina->dis_carga_horaria = $faker->randomNumber(2);
                $disciplina->dis_creditos = $faker->randomNumber(2);
                $disciplina->dis_nvc_id = $j;

                $disciplina->save();
            }
        }
    }
}
