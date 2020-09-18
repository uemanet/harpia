<?php
namespace Modulos\RH\Database\Seeds\Development;

use Illuminate\Database\Seeder;
use Modulos\RH\Models\Funcao;

class FuncaoTableSeeder extends Seeder
{
    public function run()
    {

        $funcao = new Funcao();
        $funcao->fun_descricao = 'Desenvolvedor';

        $funcao->save();

    }
}
