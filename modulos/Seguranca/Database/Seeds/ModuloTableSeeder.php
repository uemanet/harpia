<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Seguranca\Models\Modulo;

class ModuloTableSeeder extends Seeder
{

    public function run()
    {
        //Modulo Segurança
        $modulo = new Modulo;
        $modulo->mod_rota = 'seguranca';
        $modulo->mod_nome = 'Segurança';
        $modulo->mod_descricao = 'Módulo de gerenciamento de permissões de acesso do usuário';
        $modulo->mod_icone = 'fa fa-lock';
        $modulo->mod_class = 'bg-red';
        $modulo->mod_ativo = 1;
        $modulo->save();

        //Modulo Geral
        $modulo = new Modulo;
        $modulo->mod_rota = 'geral';
        $modulo->mod_nome = 'Geral';
        $modulo->mod_descricao = 'Módulo de cadastro Geral';
        $modulo->mod_icone = 'fa fa-cubes';
        $modulo->mod_class = 'bg-blue';
        $modulo->mod_ativo = 1;
        $modulo->save();

        //Modulo Acadêmico
        $modulo = new Modulo;
        $modulo->mod_rota = 'academico';
        $modulo->mod_nome = 'Acadêmico';
        $modulo->mod_descricao = 'Módulo de cadastro Acadêmico';
        $modulo->mod_icone = 'fa fa-book';
        $modulo->mod_class = 'bg-green';
        $modulo->mod_ativo = 1;
        $modulo->save();
    }
}
