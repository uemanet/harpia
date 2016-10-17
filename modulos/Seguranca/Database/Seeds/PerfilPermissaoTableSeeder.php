<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Seguranca\Models\Perfil;

class PerfilPermissaoTableSeeder extends Seeder
{
    public function run()
    {
        /** Perfil Adminsitrador do Módulo Segurança */
        $perfil = Perfil::find(1); // Perfil administrador do modulo seguranca

        $perfil->permissoes()->attach([1, 2, 3, 4]); // Permissoes do recurso modulo

        $perfil->permissoes()->attach([5, 6, 7, 8]); // Permissoes do recurso perfil

        $perfil->permissoes()->attach([9, 10, 11, 12]); // Permissoes do recurso categorias de recursos

        $perfil->permissoes()->attach([13, 14, 15, 16]); // Permissoes do recurso recursos

        $perfil->permissoes()->attach([17, 18, 19, 20]); // Permissoes do recurso recursos

        $perfil->permissoes()->attach([21, 22]); // Perfis Permissoes

        $perfil->permissoes()->attach([23, 24, 25, 26]); //Index

        /** Perfil Administrador do Módulo Geral */
        $perfil = Perfil::find(2);

        $perfil->permissoes()->attach([27]); // Permissões Dashboard

        $perfil->permissoes()->attach([28, 29, 30, 31]); //Permissões Pessoas

        /** Perfil Administrador do Módulo Acadêmico */

        $perfil = Perfil::find(3);

        $perfil->permissoes()->attach([32]); //Index Dashboard

        $perfil->permissoes()->attach([33, 34, 35, 36]); //Permissões do recurso polo

        $perfil->permissoes()->attach([37, 38, 39, 40]); // Permissoes do recurso departamentos

        $perfil->permissoes()->attach([41, 42, 43, 44]); // Permissoes do recurso periodos letivos

        $perfil->permissoes()->attach([45, 46, 47, 48]); // Permissoes do recurso cursos

        $perfil->permissoes()->attach([49, 50, 51, 52]); // Permissoes do recurso centros

        $perfil->permissoes()->attach([53, 54, 55, 56]); // Permissoes do recurso matrizes curriculares

        $perfil->permissoes()->attach([57, 58]); // Permissoes do recurso oferta de cursos

        $perfil->permissoes()->attach([59, 60, 61, 62]); // Permissoes do recurso grupos

        $perfil->permissoes()->attach([63, 64, 65, 66]); // Permissoes do recurso turma

        $perfil->permissoes()->attach([67, 68, 69, 70]); // Permissoes do recurso módulos matrizes

        $perfil->permissoes()->attach([70, 71, 72, 73]); // Permissoes do recurso vinculos

        $perfil->permissoes()->attach([74, 75, 76]); // Permissoes do recurso tutores do grupo

        $perfil->permissoes()->attach([77, 78, 79, 80]); // Permissoes do recurso disciplinas

    }
}
