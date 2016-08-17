<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Seguranca\Models\Permissao;

class PermissaoTableSeeder extends Seeder
{

    public function run()
    {
        $this->createPermissoesIndex();

        $this->createPermissoesModulo();

        $this->createPermissoesCategoriasRecursos();

        $this->createPermissoesRecursos();

        $this->createPermissoesPermissoes();

        $this->createPermissoesPerfis();

        $this->createPermissoesUsuarios();

        $this->createPermissoesGeralPolo();

        $this->createPermissoesAcademicoIndex();
    }

    private function createPermissoesIndex()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 1;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Dashboard';
        $permissao->save();
    }

    private function createPermissoesModulo()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 2;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso módulo da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 2;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso módulo da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 2;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso módulo da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 2;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso módulo da categoria segurança do módulo segurança';
        $permissao->save();
    }

    private function createPermissoesCategoriasRecursos()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 3;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso categorias de recursos da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 3;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso categorias de recursos da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 3;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso categorias de recursos da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 3;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso categorias de recursos da categoria segurança do módulo segurança';
        $permissao->save();
    }

    private function createPermissoesRecursos()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 4;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso recursos da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 4;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso recursos da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 4;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso recursos da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 4;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso recursos da categoria segurança do módulo segurança';
        $permissao->save();
    }

    private function createPermissoesPermissoes()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 5;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso permissoes da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 5;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso permissoes da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 5;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso permissoes da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 5;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso permissoes da categoria segurança do módulo segurança';
        $permissao->save();
    }

    private function createPermissoesPerfis()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 6;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso perfil da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 6;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso perfil da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 6;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso perfil da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 6;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso perfil da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 6;
        $permissao->prm_nome = 'atribuirpermissoes';
        $permissao->prm_descricao = 'Permissão de atribuir permissoes ao perfis do recurso perfil da categoria segurança do módulo segurança';
        $permissao->save();
    }

    private function createPermissoesUsuarios()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 7;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso usuario da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 7;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso usuario da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 7;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso usuario da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 7;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso usuario da categoria segurança do módulo segurança';
        $permissao->save();
    }

    private function createPermissoesGeralPolo()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 8;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso polos da categoria cadastro do módulo geral';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 8;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso polos da categoria cadastro do módulo geral';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 8;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso polos da categoria cadastro do módulo geral';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 8;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso polos da categoria cadastro do módulo geral';
        $permissao->save();
    }

    private function createPermissoesAcademicoIndex()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 9;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Dashboard';
        $permissao->save();
    }
}
