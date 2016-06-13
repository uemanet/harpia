<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Seguranca\Models\Permissao;

class PermissaoTableSeeder extends Seeder {

    public function run()
    {
        $this->createPermissoesModulo();

        $this->createPermissoesPerfil();

        $this->createCategoriasRecursos();
    }

    private function createPermissoesModulo()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 1;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso módulo da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 1;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso módulo da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 1;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso módulo da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 1;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso módulo da categoria segurança do módulo segurança';
        $permissao->save();
    }

    private function createPermissoesPerfil()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 2;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso perfil da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 2;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso perfil da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 2;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso perfil da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 2;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso perfil da categoria segurança do módulo segurança';
        $permissao->save();
    }

    private function createCategoriasRecursos()
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
}