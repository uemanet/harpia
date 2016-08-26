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

        $perfil->permissoes()->attach([32, 33, 34, 35]); //Permissões do recurso polo

        $perfil->permissoes()->attach([36, 37, 38, 39]); // Permissoes do recurso departamentos

        $perfil->permissoes()->attach([40, 41, 42, 43]); // Permissoes do recurso periodos letivos

        $perfil->permissoes()->attach([44, 45, 46, 47]); // Permissoes do recurso cursos

        $perfil->permissoes()->attach([48, 49, 50, 51]); // Permissoes do recurso centros

        $perfil->permissoes()->attach([52, 53, 54, 55]); // Permissoes do recurso matrizes curriculares

        $perfil->permissoes()->attach([56, 57, 58, 59]); // Permissoes do recurso oferta de cursos
    }
}
