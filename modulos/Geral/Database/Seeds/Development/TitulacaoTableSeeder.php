<?php

namespace Modulos\Geral\Database\Seeds\Development;

use Illuminate\Database\Seeder;
use Modulos\Geral\Models\Titulacao;

class TitulacaoTableSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create();

        $tipos = [
            'Ensino Médio' => 1,
            'Graduação' => 10,
            'Especialização' => 30,
            'Mestrado' => 40,
            'Doutorado' => 60,
            'Pós-Doutorado' => 80,
            'Pós-Graduação' => 20
        ];

        foreach ($tipos as $tipo => $peso) {
            $titulacao = new Titulacao();
            $titulacao->tit_nome = $tipo;
            $titulacao->tit_peso = $peso;
            $titulacao->tit_descricao = $faker->text(150);

            $titulacao->save();
        }
    }
}
