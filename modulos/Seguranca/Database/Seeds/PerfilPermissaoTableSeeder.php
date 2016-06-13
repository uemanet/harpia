<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Seguranca\Models\Perfil;

class PerfilPermissaoTableSeeder extends Seeder
{

    public function run()
    {
        $perfil = Perfil::find(1); // Perfil administrador do modulo seguranca
        $perfil->permissoes()->attach([1, 2, 3, 4]); // Permissoes do recurso modulo

        $perfil->permissoes()->attach([5, 6, 7, 8]); // Permissoes do recurso perfil

        $perfil->permissoes()->attach([9, 10, 11, 12]); // Permissoes do recurso categorias de recursos
    }
}
