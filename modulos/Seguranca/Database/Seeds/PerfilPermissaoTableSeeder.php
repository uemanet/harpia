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

        $perfil->permissoes()->attach([23, 24, 25, 26, 27, 28]); // Permissoes do recurso Usuários


        /** Perfil Administrador do Módulo Geral */
        $perfil = Perfil::find(2);

        $perfil->permissoes()->attach([29]); // Permissões Dashboard

        $perfil->permissoes()->attach([30, 31, 32, 33, 34]); //Permissões Pessoas

        /** Perfil Administrador do Módulo Acadêmico */

        $perfil = Perfil::find(3);

        $perfil->permissoes()->attach([35]); //Index Dashboard

        $perfil->permissoes()->attach([36, 37, 38, 39]); //Permissões do recurso polo

        $perfil->permissoes()->attach([40, 41, 42, 43]); // Permissoes do recurso departamentos

        $perfil->permissoes()->attach([44, 45, 46, 47]); // Permissoes do recurso periodos letivos

        $perfil->permissoes()->attach([48, 49, 50, 51]); // Permissoes do recurso cursos

        $perfil->permissoes()->attach([52, 53, 54, 55]); // Permissoes do recurso centros

        $perfil->permissoes()->attach([56, 57, 58, 59, 60]); // Permissoes do recurso matrizes curriculares

        $perfil->permissoes()->attach([61, 62]); // Permissoes do recurso Oferta de Cursos

        $perfil->permissoes()->attach([63, 64, 65, 66]); // Permissoes do recurso Grupos

        $perfil->permissoes()->attach([67, 68, 69, 70]); // Permissoes do recurso Turmas

        $perfil->permissoes()->attach([71, 72, 73, 74, 75]); // Permissoes do recurso Módulos Matrizes

        $perfil->permissoes()->attach([76, 77, 78, 79]); // Permissoes do recurso Disciplinas

        $perfil->permissoes()->attach([80, 81, 82, 83]); // Permissoes do recurso Vinculos

        $perfil->permissoes()->attach([84, 85, 86]); // Permissoes do recurso Tutores Grupos

        $perfil->permissoes()->attach([87, 88, 89, 90]); // Permissoes do recurso Alunos

        $perfil->permissoes()->attach([91, 92, 93, 94]); // Permissoes do recurso Tutores

        $perfil->permissoes()->attach([95, 96, 97, 98]); // Permissoes do recurso Professores

        $perfil->permissoes()->attach([99, 100, 101]); // Permissoes do recurso Matricular aluno no curso

        $perfil->permissoes()->attach([102, 103]); // Permissoes do recurso Ofertar Disciplina

        $perfil->permissoes()->attach([104, 105]); // Permissoes do recurso Matricular Aluno na Disciplina

        $perfil->permissoes()->attach([106, 107, 108, 109]); // Permissoes do recurso Titulações

        $perfil->permissoes()->attach([110, 111, 112, 113]); // Permissoes do recurso Titulações


        /** Perfil Administrador do Módulo Integração */

        $perfil = Perfil::find(4);

        $perfil->permissoes()->attach([114]); // Permissoes do recurso Dashboard

        $perfil->permissoes()->attach([115, 116, 117, 118, 119, 120]); // Permissoes do recurso Ambientes

        /** Perfil Administrador do Módulo de Monitoramento */

        $perfil = Perfil::find(5);

        $perfil->permissoes()->attach([121]); // Permissoes do recurso Dashboard

        $perfil->permissoes()->attach([122, 123]); // Permissoes do recurso Tempo Online

    }
}
