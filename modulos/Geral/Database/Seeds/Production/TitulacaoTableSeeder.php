<?php

namespace Modulos\Geral\Database\Seeds\Production;

use Illuminate\Database\Seeder;
use Modulos\Geral\Models\Titulacao;

class TitulacaoTableSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create();

        $tipos = [
            'Graduação' => 2,
            'Mestrado' => 4,
            'Doutorado' => 5,
            'Pós-doutorado' => 6,
            'Especialização' => 3,
            'Ensino Médio' => 1
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
