<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;

use Modulos\Seguranca\Models\MenuItem;
use Modulos\Seguranca\Models\Modulo;
use Modulos\Seguranca\Models\Perfil;
use Modulos\Seguranca\Models\Permissao;
use Modulos\Seguranca\Models\Usuario;

class PermissoesMonitoramentoSeeder extends Seeder
{
    public function run()
    {

        // Cria perfil de Administrador
        $perfil = Perfil::create([
            'prf_mod_id' => 5,
            'prf_nome' => 'Administrador Monitoramento'
        ]);
        $arrPermissoes = [];

        // Criar permissao index do modulo Integracao (DASHBOARD)
        $permissao = Permissao::create([
            'prm_nome' => 'dashboard',
            'prm_rota' => 'monitoramento.index.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;



        //permissões do recurso tempoonline
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'monitoramento.tempoonline.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'monitorar',
            'prm_rota' => 'monitoramento.tempoonline.monitorar'
        ]);
        $arrPermissoes[] = $permissao->prm_id;



        // Atirbuir permissao index ao perfil de Administrador
        $perfil->permissoes()->attach($arrPermissoes);

        // Atribuir perfil de Administrador ao usuario criado
        $perfil->usuarios()->attach(1);
    }
}
