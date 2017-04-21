<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;

use Modulos\Seguranca\Models\MenuItem;
use Modulos\Seguranca\Models\Modulo;
use Modulos\Seguranca\Models\Perfil;
use Modulos\Seguranca\Models\Permissao;
use Modulos\Seguranca\Models\Usuario;

class MenuIntegracaoSeeder extends Seeder
{
    public function run()
    {
        // Criando itens no menu

        // Categoria Integração
        $integracao = MenuItem::create([
            'mit_mod_id' => 4,
            'mit_nome' => 'Integração',
            'mit_icone' => 'fa fa-exchange',
            'mit_ordem' => 1
        ]);

        // Item Dashboard
        $dashboard = MenuItem::create([
            'mit_mod_id' => 4,
            'mit_item_pai' => $integracao->mit_id,
            'mit_nome' => 'Dashboard',
            'mit_icone' => 'fa fa-dashboard',
            'mit_rota' => 'integracao.index.index',
            'mit_ordem' => 1
        ]);

        // Subcategoria Cadastros
        $cadastro = MenuItem::create([
            'mit_mod_id' => 4,
            'mit_item_pai' => $integracao->mit_id,
            'mit_nome' => 'Cadastros',
            'mit_icone' => 'fa fa-plus',
            'mit_ordem' => 2
        ]);

        // Item Ambiente Virtual
        $ambienteVirtual = MenuItem::create([
            'mit_mod_id' => 4,
            'mit_item_pai' => $cadastro->mit_id,
            'mit_nome' => 'Ambientes Virtuais',
            'mit_icone' => 'fa fa-laptop',
            'mit_rota' => 'integracao.ambientesvirtuais.index',
            'mit_ordem' => 1
        ]);
    }
}
