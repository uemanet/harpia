<?php

namespace Modulos\Academico\Database\Seeds;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Modulos\Academico\Models\MatrizCurricular;
use Modulos\Geral\Models\Anexo;

class MatrizCurricularSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create();

        for ($i=1;$i<=5;$i++) {
            $matriz = new MatrizCurricular();
            $matriz->mtc_crs_id = $i;
            $matriz->mtc_anx_projeto_pedagogico = factory(Anexo::class)->create()->anx_id;
            $matriz->mtc_titulo = 'Matriz 1';
            $matriz->mtc_descricao = "Matriz Nº 1";
            $matriz->mtc_data = $faker->date('d/m/Y');
            $matriz->mtc_creditos = 50;
            $matriz->mtc_horas = 800;
            $matriz->mtc_horas_praticas = 250;
            $matriz->save();
        }
    }
}
