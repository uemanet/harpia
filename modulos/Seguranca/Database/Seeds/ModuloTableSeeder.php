<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Seguranca\Models\Modulo;

class ModuloTableSeeder extends Seeder {

    public function run()
    {
        $modulo = new Modulo;
        $modulo->mod_nome = 'Seguranca';
        $modulo->mod_descricao = 'Módulo de segurança';
        $modulo->mod_icone = 'fa fa-lock';
        $modulo->mod_style = '';
        $modulo->mod_ativo = 1;
        $modulo->save();
    }
}