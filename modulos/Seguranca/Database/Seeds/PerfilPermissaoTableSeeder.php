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

        $perfil->permissoes()->attach([13, 14, 15, 16]); // Permissoes do recurso recursos

        $perfil->permissoes()->attach([17, 18, 19, 20]); // Permissoes do recurso recursos

        $perfil->permissoes()->attach([21, 22]); // Perfis Permissoes

        $perfil->permissoes()->attach([23, 24, 25, 26]); //Index

        $perfil = Perfil::find(2);
        $perfil->permissoes()->attach([27, 28, 29, 30]); //Geral Polo

        $perfil = Perfil::find(3);
        $perfil->permissoes()->attach([31]); //Index Dashboard
    }
}
