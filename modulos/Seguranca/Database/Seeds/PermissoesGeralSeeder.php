<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;

use Modulos\Seguranca\Models\MenuItem;
use Modulos\Seguranca\Models\Modulo;
use Modulos\Seguranca\Models\Perfil;
use Modulos\Seguranca\Models\Permissao;
use Modulos\Seguranca\Models\Usuario;

class PermissoesGeralSeeder extends Seeder
{
    public function run()
    {

        // Cria perfil de Administrador
        $perfil = Perfil::create([
            'prf_mod_id' => 2,
            'prf_nome' => 'Administrador Geral'
        ]);
        $arrPermissoes = [];

        // Criar permissao index do modulo Geral (DASHBOARD)
        $permissao = Permissao::create([
            'prm_nome' => 'dashboard',
            'prm_rota' => 'geral.index.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        //permissões do recurso pessoas
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'geral.pessoas.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'create',
            'prm_rota' => 'geral.pessoas.create'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'edit',
            'prm_rota' => 'geral.pessoas.edit'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'show',
            'prm_rota' => 'geral.pessoas.show'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'verificapessoa',
            'prm_rota' => 'geral.pessoas.verificapessoa'
        ]);
        $arrPermissoes[] = $permissao->prm_id;


        //permissões do recurso documentos
        $permissao = Permissao::create([
            'prm_nome' => 'create',
            'prm_rota' => 'geral.pessoas.documentos.create'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'edit',
            'prm_rota' => 'geral.pessoas.documentos.edit'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'delete',
            'prm_rota' => 'geral.pessoas.documentos.delete'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'deleteanexo',
            'prm_rota' => 'geral.pessoas.documentos.deleteanexo'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'anexo',
            'prm_rota' => 'geral.pessoas.documentos.anexo'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        //permissões do recurso titulacoes
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'geral.titulacoes.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'create',
            'prm_rota' => 'geral.titulacoes.create'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'edit',
            'prm_rota' => 'geral.titulacoes.edit'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'delete',
            'prm_rota' => 'geral.titulacoes.delete'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        //permissões do recurso titulacoesinformacoes
        $permissao = Permissao::create([
            'prm_nome' => 'create',
            'prm_rota' => 'geral.pessoas.titulacoesinformacoes.create'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'edit',
            'prm_rota' => 'geral.pessoas.titulacoesinformacoes.edit'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'delete',
            'prm_rota' => 'geral.pessoas.titulacoesinformacoes.delete'
        ]);
        $arrPermissoes[] = $permissao->prm_id;


        // Atirbuir permissao index ao perfil de Administrador
        $perfil->permissoes()->attach($arrPermissoes);

        // Atribuir perfil de Administrador ao usuario criado
        $perfil->usuarios()->attach(1);
    }
}
