<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Seguranca\Models\Recurso;

class RecursoTableSeeder extends Seeder
{
    public function run()
    {
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 1; // Categoria Seguranca
        $recurso->rcs_nome = 'Dashboard';
        $recurso->rcs_rota = 'index';
        $recurso->rcs_descricao = 'Recurso dashboard da categoria segurança do módulo segurança';
        $recurso->rcs_icone = 'fa fa-tachometer';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 1;
        $recurso->save();

        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 1; // Categoria Seguranca
        $recurso->rcs_nome = 'Modulos';
        $recurso->rcs_rota = 'modulos';
        $recurso->rcs_descricao = 'Recurso módulo da dategoria segurança do módulo segurança';
        $recurso->rcs_icone = 'fa fa-cubes';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 2;
        $recurso->save();

        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 1; // Categoria Seguranca
        $recurso->rcs_nome = 'Categorias de recursos';
        $recurso->rcs_rota = 'categoriasrecursos';
        $recurso->rcs_descricao = 'Recurso categorias de recursos da dategoria segurança do módulo segurança';
        $recurso->rcs_icone = 'fa fa-indent';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 3;
        $recurso->save();

        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 1; // Categoria Seguranca
        $recurso->rcs_nome = 'Recursos';
        $recurso->rcs_rota = 'recursos';
        $recurso->rcs_descricao = 'Recurso recursos da dategoria segurança do módulo segurança';
        $recurso->rcs_icone = 'fa fa-puzzle-piece';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 4;
        $recurso->save();

        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 1; // Categoria Seguranca
        $recurso->rcs_nome = 'Permissões';
        $recurso->rcs_rota = 'permissoes';
        $recurso->rcs_descricao = 'Recurso permissões da dategoria segurança do módulo segurança';
        $recurso->rcs_icone = 'fa fa-unlock-alt';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 5;
        $recurso->save();

        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 1; // Categoria Seguranca
        $recurso->rcs_nome = 'Perfis';
        $recurso->rcs_rota = 'perfis';
        $recurso->rcs_descricao = 'Recurso perfil da dategoria segurança do módulo segurança';
        $recurso->rcs_icone = 'fa fa-user-secret';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 6;
        $recurso->save();

        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 1; // Categoria Seguranca
        $recurso->rcs_nome = 'Usuários';
        $recurso->rcs_rota = 'usuarios';
        $recurso->rcs_descricao = 'Recurso usuários da categoria segurança do módulo segurança';
        $recurso->rcs_icone = 'fa fa-users';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 7;
        $recurso->save();

        //MODULO GERAL
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 2; // Categoria Seguranca
        $recurso->rcs_nome = 'Polos';
        $recurso->rcs_rota = 'polos';
        $recurso->rcs_descricao = 'Polos de ensino';
        $recurso->rcs_icone = 'fa fa-ellipsis-h';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 1;
        $recurso->save();

        // MODULO ACADEMICO

        // Recurso Dashboard
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 3; // Categoria Acadêmico
        $recurso->rcs_nome = 'Dashboard';
        $recurso->rcs_rota = 'index';
        $recurso->rcs_descricao = 'Recurso dashboard do módulo acadêmico';
        $recurso->rcs_icone = 'fa fa-tachometer';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 1;
        $recurso->save();

        // Recurso Polos
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 3; // Categoria Acadêmico
        $recurso->rcs_nome = 'Polos';
        $recurso->rcs_rota = 'polos';
        $recurso->rcs_descricao = 'Recurso polos da categoria cadastro do módulo acadêmico';
        $recurso->rcs_icone = 'fa fa-ellipsis-h';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 2;
        $recurso->save();

        // Recurso Departamentos
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 3; // Categoria Acadêmico
        $recurso->rcs_nome = 'Departamentos';
        $recurso->rcs_rota = 'departamentos';
        $recurso->rcs_descricao = 'Recurso departamento do módulo acadêmico';
        $recurso->rcs_icone = 'fa fa-sitemap';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 3;
        $recurso->save();

        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 3; // Categoria Acadêmico
        $recurso->rcs_nome = 'Períodos Letivos';
        $recurso->rcs_rota = 'periodosletivos';
        $recurso->rcs_descricao = 'Recurso período letivo do módulo acadêmico';
        $recurso->rcs_icone = 'fa fa-calendar';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 4;
        $recurso->save();

        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 3; // Categoria Acadêmico
        $recurso->rcs_nome = 'Cursos';
        $recurso->rcs_rota = 'cursos';
        $recurso->rcs_descricao = 'Recurso curso do módulo acadêmico';
        $recurso->rcs_icone = 'fa fa-graduation-cap';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 5;
        $recurso->save();

        // Recursos Centros
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 3; // Categoria Acadêmico
        $recurso->rcs_nome = 'Centros';
        $recurso->rcs_rota = 'centros';
        $recurso->rcs_descricao = 'Recurso centro do módulo acadêmico';
        $recurso->rcs_icone = 'fa fa-building';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 6;
        $recurso->save();
    }
}
