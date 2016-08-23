<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Seguranca\Models\Perfil;

class PerfilTableSeeder extends Seeder
{
    public function run()
    {
        $perfil = new Perfil();
        $perfil->prf_mod_id = 1; // Modulo Seguranca
        $perfil->prf_nome = 'Administrador';
        $perfil->prf_descricao = 'Perfil administrador do módulo segurança';
        $perfil->save();

        //Modulo Geral
        $perfil = new Perfil();
        $perfil->prf_mod_id = 2; // Modulo Seguranca
        $perfil->prf_nome = 'Administrador';
        $perfil->prf_descricao = 'Perfil administrador do módulo geral';
        $perfil->save();

        //Modulo Acadêmico
        $perfil = new Perfil();
        $perfil->prf_mod_id = 3; // Modulo Seguranca
        $perfil->prf_nome = 'Administrador';
        $perfil->prf_descricao = 'Perfil administrador do módulo acadêmico';
        $perfil->save();
    }
}
