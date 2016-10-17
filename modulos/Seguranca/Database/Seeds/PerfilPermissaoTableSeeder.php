<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Seguranca\Models\Perfil;

class PerfilPermissaoTableSeeder extends Seeder
{
    public function run()
    {
        /** Perfil Administrador do Módulo Segurança */

        $perfil = Perfil::find(1); // Perfil administrador do modulo seguranca

        $perfil->permissoes()->attach([1]); // Permissão Index

        $perfil->permissoes()->attach([2, 3, 4, 5]); // Permissoes do recurso Módulo

        $perfil->permissoes()->attach([6, 7, 8, 9]); // Permissoes do recurso CategoriasRecursos

        $perfil->permissoes()->attach([10, 11, 12, 13]); // Permissoes do recurso Recursos

        $perfil->permissoes()->attach([14, 15, 16, 17]); // Permissoes do recurso Permissões

        $perfil->permissoes()->attach([18, 19, 20, 21, 22]); // Permissoes do recurso Perfis

        $perfil->permissoes()->attach([23, 24, 25, 26]); // Permissoes do recurso Usuários


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

        $perfil->permissoes()->attach([57, 58]); // Permissoes do recurso Oferta de Cursos

        $perfil->permissoes()->attach([59, 60, 61, 62]); // Permissoes do recurso Grupos

        $perfil->permissoes()->attach([63, 64, 65, 66]); // Permissoes do recurso Turmas

        $perfil->permissoes()->attach([67, 68, 69, 70]); // Permissoes do recurso Módulos Matrizes

        $perfil->permissoes()->attach([71, 72, 73, 74]); // Permissoes do recurso Disciplinas

        $perfil->permissoes()->attach([75, 76, 77, 78]); // Permissoes do recurso Vinculos

        $perfil->permissoes()->attach([79, 80, 81]); // Permissoes do recurso Tutores Grupos

    }
}
