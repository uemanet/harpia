<?php
namespace Modulos\Geral\Database\Seeds\Development;

use Harpia\Format\Format;
use Illuminate\Database\Seeder;
use Modulos\Geral\Models\Documento;
use Modulos\Geral\Models\Pessoa;

class PessoaTableSeeder extends Seeder
{
    public function run()
    {
        // Cadastrar 50 pessoas na base

        $faker = \Faker\Factory::create('pt_BR');
        $format = new Format();

        for ($i=0;$i<500;$i++) {
            $pessoa = new Pessoa();

            $pessoa->pes_nome = $faker->firstName.' '.$faker->lastName;
            $pessoa->pes_sexo = $faker->randomElement(array('M', 'F'));
            $nome = explode(' ', $pessoa->pes_nome);
            $pessoa->pes_email = $this->utf8_strtr($nome[0].'.'.end($nome).$faker->randomNumber(3).'@gmail.com');
            $pessoa->pes_telefone = $faker->areaCode.$faker->cellphone(false, true);
            $pessoa->pes_nascimento = $faker->date('d/m/Y');
            $pessoa->pes_mae = $faker->firstNameFemale.' '.$faker->lastName;
            $pessoa->pes_pai = $faker->firstNameMale.' '.$faker->lastName;
            $pessoa->pes_estado_civil = $faker->randomElement(array('solteiro', 'casado'));
            $pessoa->pes_naturalidade = $faker->state;
            $pessoa->pes_nacionalidade = 'Brasileira';
            $pessoa->pes_raca = $faker->randomElement(array('branca', 'preta', 'parda', 'amarela', 'indigena'));
            $pessoa->pes_necessidade_especial = 'N';
            $pessoa->pes_estrangeiro = 0;
            $pessoa->pes_endereco = $faker->streetAddress;
            $pessoa->pes_complemento = $faker->streetName;
            $pessoa->pes_numero = $faker->randomNumber(4);
            $pessoa->pes_cep = $faker->postcode;
            $pessoa->pes_cidade = $faker->city;
            $pessoa->pes_bairro = $faker->streetSuffix;
            $pessoa->pes_estado = $faker->stateAbbr;


            $pessoa->save();
            
            $documento = new Documento();
            
            $documento->doc_pes_id = $pessoa->pes_id;
            $documento->doc_tpd_id = 2;
            $documento->doc_conteudo = $format->generateCpf();

            $documento->save();

            $documento = new Documento();

            $documento->doc_pes_id = $pessoa->pes_id;
            $documento->doc_tpd_id = 1;
            $documento->doc_conteudo = $faker->rg;
            $documento->doc_orgao = 'SSP/BR';
            $documento->doc_data_expedicao = date('d/m/Y');

            $documento->save();
        }
    }

    private function utf8_strtr($string)
    {
        $from = "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ";
        $to = "aaaaeeiooouucAAAAEEIOOOUUC";

        $keys = array();
        $values = array();

        preg_match_all('/./u', $from, $keys);
        preg_match_all('/./u', $to, $values);
        $mapping = array_combine($keys[0], $values[0]);

        return strtr($string, $mapping);
    }
}
