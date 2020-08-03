<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;

use Modulos\Seguranca\Models\MenuItem;
use Modulos\Seguranca\Models\Modulo;
use Modulos\Seguranca\Models\Perfil;
use Modulos\Seguranca\Models\Permissao;
use Modulos\Seguranca\Models\Usuario;

class PermissoesRHSeeder extends Seeder
{
    public function run()
    {

        // Cria perfil de Administrador
        $perfil = Perfil::create([
            'prf_mod_id' => 6,
            'prf_nome' => 'Administrador RH'
        ]);
        $arrPermissoes = [];


        // Criar permissao index do modulo RH (DASHBOARD)
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'rh.index.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;


        //permissões do recurso áreas de conhecimentos
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'rh.areasconhecimentos.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'create',
            'prm_rota' => 'rh.areasconhecimentos.create'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'edit',
            'prm_rota' => 'rh.areasconhecimentos.edit'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'delete',
            'prm_rota' => 'rh.areasconhecimentos.delete'
        ]);
        $arrPermissoes[] = $permissao->prm_id;


        //permissões do recurso bancos
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'rh.bancos.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'create',
            'prm_rota' => 'rh.bancos.create'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'edit',
            'prm_rota' => 'rh.bancos.edit'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'delete',
            'prm_rota' => 'rh.bancos.delete'
        ]);
        $arrPermissoes[] = $permissao->prm_id;


        //permissões do recurso de vinculos
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'rh.vinculos.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'create',
            'prm_rota' => 'rh.vinculos.create'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'edit',
            'prm_rota' => 'rh.vinculos.edit'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'delete',
            'prm_rota' => 'rh.vinculos.delete'
        ]);
        $arrPermissoes[] = $permissao->prm_id;


        //permissões do recurso de funcoes
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'rh.funcoes.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'create',
            'prm_rota' => 'rh.funcoes.create'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'edit',
            'prm_rota' => 'rh.funcoes.edit'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'delete',
            'prm_rota' => 'rh.funcoes.delete'
        ]);
        $arrPermissoes[] = $permissao->prm_id;


        //permissões do recurso de setores
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'rh.setores.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'create',
            'prm_rota' => 'rh.setores.create'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'edit',
            'prm_rota' => 'rh.setores.edit'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'delete',
            'prm_rota' => 'rh.setores.delete'
        ]);
        $arrPermissoes[] = $permissao->prm_id;


        //permissões do recurso de periodoslaborais
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'rh.periodoslaborais.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'create',
            'prm_rota' => 'rh.periodoslaborais.create'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'edit',
            'prm_rota' => 'rh.periodoslaborais.edit'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'delete',
            'prm_rota' => 'rh.periodoslaborais.delete'
        ]);
        $arrPermissoes[] = $permissao->prm_id;


        //permissões do recurso de colaboradores
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'rh.colaboradores.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'create',
            'prm_rota' => 'rh.colaboradores.create'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'edit',
            'prm_rota' => 'rh.colaboradores.edit'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'delete',
            'prm_rota' => 'rh.colaboradores.delete'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        // Atirbuir permissao index ao perfil de Administrador
        $perfil->permissoes()->attach($arrPermissoes);

        // Atribuir perfil de Administrador ao usuario criado
        $perfil->usuarios()->attach(1);

    }
}
