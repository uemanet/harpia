<?php
namespace Modulos\Geral\Database\Seeds;

use Harpia\Format\Format;
use Illuminate\Database\Seeder;
use Modulos\Geral\Models\Documento;
use Modulos\Geral\Models\Pessoa;

class PessoaTableSeeder extends Seeder
{
    public function run()
    {
        // Cadastrar 50 pessoas na base

        $faker = \Faker\Factory::create();
        $format = new Format();

        for ($i=0;$i<200;$i++) {
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
            $pessoa->pes_nacionalidade = 'Brasileira';
            $pessoa->pes_raca = $faker->randomElement(array('branca', 'preta', 'parda', 'amarela', 'indigena'));
            $pessoa->pes_necessidade_especial = 'Não';
            $pessoa->pes_estrangeiro = 0;
            $pessoa->pes_endereco = $faker->streetAddress;
            $pessoa->pes_complemento = $faker->streetName;
            $pessoa->pes_numero = $faker->randomNumber(4);
            $pessoa->pes_cep = $faker->postcode;
            $pessoa->pes_cidade = $faker->city;
            $pessoa->pes_bairro = $faker->randomElement(array('João Paulo', 'Cohab', 'Cohama', 'Coroadinho', 'Barreto'));
            $pessoa->pes_estado = $faker->randomElement(array('MA', 'SP', 'CE', 'PI'));


            $pessoa->save();
            
            $documento = new Documento();
            
            $documento->doc_pes_id = $pessoa->pes_id;
            $documento->doc_tpd_id = 2;
            $documento->doc_conteudo = $format->generateCpf();

            $documento->save();
        }
    }
}
