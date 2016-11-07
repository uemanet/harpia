<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Seguranca\Models\Perfil;

class PerfilTableSeeder extends Seeder
{
    public function run()
    {
        // Modulo Seguranca
        $perfil = new Perfil();
        $perfil->prf_mod_id = 1;
        $perfil->prf_nome = 'Administrador';
        $perfil->prf_descricao = 'Perfil administrador do módulo segurança';
        $perfil->save();

        // Modulo Geral
        $perfil = new Perfil();
        $perfil->prf_mod_id = 2;
        $perfil->prf_nome = 'Administrador';
        $perfil->prf_descricao = 'Perfil administrador do módulo geral';
        $perfil->save();

        // Modulo Acadêmico
        $perfil = new Perfil();
        $perfil->prf_mod_id = 3;
        $perfil->prf_nome = 'Administrador';
        $perfil->prf_descricao = 'Perfil administrador do módulo acadêmico';
        $perfil->save();

        // Modulo Integração
        $perfil = new Perfil();
        $perfil->prf_mod_id = 4;
        $perfil->prf_nome = 'Administrador';
        $perfil->prf_descricao = 'Perfil administrador do módulo integração';
        $perfil->save();
    }
}
