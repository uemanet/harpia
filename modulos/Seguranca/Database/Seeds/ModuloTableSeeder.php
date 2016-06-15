<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Seguranca\Models\Modulo;

class ModuloTableSeeder extends Seeder
{

    public function run()
    {
        $modulo = new Modulo;
        $modulo->mod_rota = 'seguranca';
        $modulo->mod_nome = 'Segurança';
        $modulo->mod_descricao = 'Módulo de gerenciamento de permissões de acesso do usuário';
        $modulo->mod_icone = 'fa fa-lock';
        $modulo->mod_class = 'bg-red';
        $modulo->mod_ativo = 1;
        $modulo->save();
    }
}