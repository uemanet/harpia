<?php

namespace Modulos\Academico\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Academico\Models\Disciplina;

class DisciplinasTableSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create();

        // criar 30 disciplinas
        for($i=0;$i<30;$i++)
        {
            $disciplina = new Disciplina();

            $disciplina->dis_nome = $faker->sentence(2);
            $disciplina->dis_carga_horaria = $faker->randomNumber(2);
            $disciplina->dis_creditos = $faker->randomNumber(2);
            $disciplina->dis_nvc_id = $faker->randomElement([1,2,3,4,5]);

            $disciplina->save();
        }
    }
}