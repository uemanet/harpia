<?php

namespace Modulos\Academico\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Academico\Models\Professor;

class ProfessorTableSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create();

        for ($i=2;$i<=11;$i++) {
            $professor = new Professor();

            $professor->prf_pes_id = $i;
            $professor->prf_matricula = $faker->ean13;

            $professor->save();
        }
    }
}
