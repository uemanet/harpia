<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;

use Modulos\Seguranca\Models\MenuItem;
use Modulos\Seguranca\Models\Modulo;
use Modulos\Seguranca\Models\Perfil;
use Modulos\Seguranca\Models\Permissao;
use Modulos\Seguranca\Models\Usuario;

class PermissoesIntegracaoSeeder extends Seeder
{
    public function run()
    {

        // Cria perfil de Administrador
        $perfil = Perfil::create([
            'prf_mod_id' => 4,
            'prf_nome' => 'Administrador Integracao'
        ]);
        $arrPermissoes = [];

        // Criar permissao index do modulo Integracao (DASHBOARD)
        $permissao = Permissao::create([
            'prm_nome' => 'dashboard',
            'prm_rota' => 'integracao.index.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;


        //permissões do recurso ambientesvirtuais
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'integracao.ambientesvirtuais.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'create',
            'prm_rota' => 'integracao.ambientesvirtuais.create'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'edit',
            'prm_rota' => 'integracao.ambientesvirtuais.edit'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'delete',
            'prm_rota' => 'integracao.ambientesvirtuais.delete'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'adicionarservico',
            'prm_rota' => 'integracao.ambientesvirtuais.adicionarservico'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'adicionarturma',
            'prm_rota' => 'integracao.ambientesvirtuais.adicionarturma'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'deletarturma',
            'prm_rota' => 'integracao.ambientesvirtuais.deletarturma'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'integracao.mapeamentonotas.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;


        // Atirbuir permissao index ao perfil de Administrador
        $perfil->permissoes()->attach($arrPermissoes);

        // Atribuir perfil de Administrador ao usuario criado
        $perfil->usuarios()->attach(1);
    }
}
