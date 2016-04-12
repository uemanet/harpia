<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;

use Modulos\Seguranca\Models\Modulo;

class ModuloTableSeeder extends Seeder {

    public function run()
    {
        $usuario = new Modulo;
        $usuario->mod_nome = 'Seguranca';
        $usuario->mod_descricao = 'Módulo de segurança';
        $usuario->mod_icone = 'fa-lock';
        $usuario->mod_style = '';
        $usuario->mod_ativo = 1;
        $usuario->save();
    }
}