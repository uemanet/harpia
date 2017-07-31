<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;

use Modulos\Seguranca\Models\MenuItem;
use Modulos\Seguranca\Models\Modulo;
use Modulos\Seguranca\Models\Perfil;
use Modulos\Seguranca\Models\Permissao;
use Modulos\Seguranca\Models\Usuario;

class PermissoesSegurancaSeeder extends Seeder
{
    public function run()
    {

        // Cria perfil de Administrador
        $perfil = Perfil::create([
            'prf_mod_id' => 1,
            'prf_nome' => 'Administrador Segurança'
        ]);
        $arrPermissoes = [];

        // Criar permissao index do modulo Integracao (DASHBOARD)
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'seguranca.index.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;


        //permissões do recurso perfis
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'seguranca.perfis.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'atribuirpermissoes',
            'prm_rota' => 'seguranca.perfis.atribuirpermissoes'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'create',
            'prm_rota' => 'seguranca.perfis.create'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'edit',
            'prm_rota' => 'seguranca.perfis.edit'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'delete',
            'prm_rota' => 'seguranca.perfis.delete'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        //permissões do recurso usuarios
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'seguranca.usuarios.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'create',
            'prm_rota' => 'seguranca.usuarios.create'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'edit',
            'prm_rota' => 'seguranca.usuarios.edit'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'delete',
            'prm_rota' => 'seguranca.usuarios.delete'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'atribuirperfil',
            'prm_rota' => 'seguranca.usuarios.atribuirperfil'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'deletarperfil',
            'prm_rota' => 'seguranca.usuarios.deletarperfil'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'seguranca.permissoes.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'create',
            'prm_rota' => 'seguranca.permissoes.create'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'edit',
            'prm_rota' => 'seguranca.permissoes.edit'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'delete',
            'prm_rota' => 'seguranca.permissoes.delete'
        ]);
        $arrPermissoes[] = $permissao->prm_id;


        // Atirbuir permissao index ao perfil de Administrador
        $perfil->permissoes()->attach($arrPermissoes);

        // Atribuir perfil de Administrador ao usuario criado
        $perfil->usuarios()->attach(1);
    }
}
