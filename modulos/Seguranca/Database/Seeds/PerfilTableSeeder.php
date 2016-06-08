<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Seguranca\Models\Perfil;

class PerfilTableSeeder extends Seeder {

    public function run()
    {
        $perfil = new Perfil();
        $perfil->prf_mod_id = 1; // Modulo Seguranca
        $perfil->prf_nome = 'Administrador';
        $perfil->prf_descricao = 'Perfil administrador do módulo segurança';
        $perfil->save();
    }
}