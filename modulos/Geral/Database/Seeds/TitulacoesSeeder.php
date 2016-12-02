<?php

namespace Modulos\Geral\Database\Seeds;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Modulos\Geral\Models\Titulacao;

class TitulacoesSeeder extends Seeder
{
    public function run()
    {

        $faker = Factory::create();

        $tipos = [
            'ENSINO MÉDIO',
            'GRADUAÇÃO',
            'ESPECIALIZAÇÃO',
            'MESTRADO',
            'DOUTORADO',
            'PÓS-DOUTORADO',
            'PÓS-GRADUAÇÃO'
        ];

        foreach ($tipos as $tipo) {
            $obj = new Titulacao();
            $obj->tit_nome = $tipo;
            $obj->tit_descricao = $faker->realText(10, 2);
            $obj->tit_peso = $faker->randomNumber(2);
            $obj->save();
        }
    }
}
