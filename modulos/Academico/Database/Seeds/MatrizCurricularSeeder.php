<?php

namespace Modulos\Academico\Database\Seeds;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Modulos\Academico\Models\Curso;
use Modulos\Academico\Models\MatrizCurricular;
use Modulos\Geral\Models\Anexo;

class MatrizCurricularSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create('pt_BR');

        $cursos = Curso::all();

        foreach ($cursos as $curso) {
            $matriz = new MatrizCurricular();
            $matriz->mtc_crs_id = $curso->crs_id;
            $matriz->mtc_anx_projeto_pedagogico = factory(Anexo::class)->create()->anx_id;
            $matriz->mtc_titulo = 'Matriz 1';
            $matriz->mtc_descricao = "Matriz Principal do Curso";
            $matriz->mtc_data = $faker->date('d/m/Y');
            $matriz->mtc_creditos = 50;
            $matriz->mtc_horas = 800;
            $matriz->mtc_horas_praticas = 250;
            $matriz->save();
        }
    }
}
