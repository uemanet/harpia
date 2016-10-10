<?php

namespace Modulos\Academico\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Academico\Models\Curso;
use Faker\Factory;

class CursoTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create();

        $curso = new Curso();
        $curso->crs_dep_id = 1;
        $curso->crs_nvc_id = 3;
        $curso->crs_prf_diretor = 1;
        $curso->crs_nome = 'Ciência da Computação';
        $curso->crs_sigla = 'CC';
        $curso->crs_descricao = 'Curso Superior em Ciência da Computação';
        $curso->crs_resolucao = 'MEC 45/2014';
        $curso->crs_autorizacao = 'MEC 15/2014';
        $curso->crs_data_autorizacao = $faker->date('d/m/Y');
        $curso->crs_eixo = 'Computação';
        $curso->crs_habilitacao = 'Bacharel';
        $curso->save();
    }
}
