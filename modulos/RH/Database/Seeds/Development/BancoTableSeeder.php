<?php
namespace Modulos\RH\Database\Seeds\Development;

use Illuminate\Database\Seeder;
use Modulos\RH\Models\Banco;

class BancoTableSeeder extends Seeder
{
    public function run()
    {


        $banco = new Banco();
        $banco->ban_nome = 'Banco do Brasil';
        $banco->ban_codigo = '101';
        $banco->ban_sigla = 'BB';

        $banco->save();


        $banco = new Banco();
        $banco->ban_nome = 'Caixa Econômica Federal';
        $banco->ban_codigo = '104';
        $banco->ban_sigla = 'CEF';

        $banco->save();


    }
}
