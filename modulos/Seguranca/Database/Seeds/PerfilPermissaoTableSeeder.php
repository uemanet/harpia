<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Seguranca\Models\Perfil;

class PerfilPermissaoTableSeeder extends Seeder {

    public function run()
    {
        $perfil = Perfil::find(1); // Perfil administrador do modulo seguranca
        $perfil->permissoes()->attach([1, 2, 3, 4]);
    }
}