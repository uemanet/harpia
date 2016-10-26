<?php
namespace Modulos\Geral\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Geral\Models\Pessoa;

class PessoaTableSeeder extends Seeder
{
    public function run()
    {
        // Cadastrar 50 pessoas na base

        $faker = \Faker\Factory::create();

        for ($i=0;$i<50;$i++) {
            $pessoa = new Pessoa();

            $pessoa->pes_nome = $faker->name;
            $pessoa->pes_sexo = $faker->randomElement(array('M', 'F'));
            $pessoa->pes_email = $faker->email;
            $pessoa->pes_telefone = '98988992233';
            $pessoa->pes_nascimento = $faker->date('d/m/Y');
            $pessoa->pes_mae = $faker->name;
            $pessoa->pes_pai = $faker->name;
            $pessoa->pes_estado_civil = $faker->randomElement(array('solteiro', 'casado'));
            $pessoa->pes_naturalidade = $faker->randomElement(array('Maranhao', 'Sao Paulo', 'Ceará', 'Piauí'));
            $pessoa->pes_nacionalidade = 'Brasil';
            $pessoa->pes_raca = $faker->randomElement(array('branco', 'pardo', 'negro', 'mulato'));
            $pessoa->pes_necessidade_especial = 'Não';
            $pessoa->pes_estrangeiro = 0;

            $pessoa->save();
        }
    }
}
