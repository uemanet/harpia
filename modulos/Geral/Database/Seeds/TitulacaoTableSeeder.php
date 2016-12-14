<?php

namespace Modulos\Geral\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Geral\Models\Titulacao;

class TitulacaoTableSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create();

        $tipos = [
            'Ensino Médio',
            'Graduação',
            'Especialização',
            'Mestrado',
            'Doutorado',
            'Pós-Doutorado',
            'Pós-Graduação'
        ];

        foreach ($tipos as $tipo) {
            $titulacao = new Titulacao();
            $titulacao->tit_nome = $tipo;
            $titulacao->tit_peso = $faker->randomNumber(2);
            $titulacao->tit_descricao = $faker->text(150);

            $titulacao->save();
        }
    }
}
