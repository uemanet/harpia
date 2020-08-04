<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;

use Modulos\Seguranca\Models\MenuItem;
use Modulos\Seguranca\Models\Modulo;
use Modulos\Seguranca\Models\Perfil;
use Modulos\Seguranca\Models\Permissao;
use Modulos\Seguranca\Models\Usuario;

class MenuRHSeeder extends Seeder
{
    public function run()
    {
        // Criando itens no menu

        // Categoria Monitoramento
        $rh = MenuItem::create([
            'mit_mod_id' => 6,
            'mit_nome' => 'Recursos Humanos',
            'mit_icone' => 'fa fa-file-text',
            'mit_ordem' => 1
        ]);

        // Item Dashboard
        MenuItem::create([
            'mit_mod_id' => 6,
            'mit_nome' => 'Dashboard',
            'mit_item_pai' => $rh->mit_id,
            'mit_icone' => 'fa fa-tachometer',
            'mit_rota' => 'rh.index.index',
            'mit_ordem' => 1
        ]);

        // Categoria cadastros
        $rh = MenuItem::create([
            'mit_mod_id' => 6,
            'mit_nome' => 'Cadastros',
            'mit_icone' => 'fa fa-plus',
            'mit_ordem' => 1
        ]);

        // Item areas conhecimentos
        MenuItem::create([
            'mit_mod_id' => 6,
            'mit_nome' => 'Áreas de Conhecimento',
            'mit_item_pai' => $rh->mit_id,
            'mit_icone' => 'fa fa-tachometer',
            'mit_rota' => 'rh.areasconhecimentos.index',
            'mit_ordem' => 1
        ]);

        // Item bancos
        MenuItem::create([
            'mit_mod_id' => 6,
            'mit_nome' => 'Bancos',
            'mit_item_pai' => $rh->mit_id,
            'mit_icone' => 'fa fa-bank',
            'mit_rota' => 'rh.bancos.index',
            'mit_ordem' => 2
        ]);

        // Item vinculos
        MenuItem::create([
            'mit_mod_id' => 6,
            'mit_nome' => 'Vínculos',
            'mit_item_pai' => $rh->mit_id,
            'mit_icone' => 'fa fa-link',
            'mit_rota' => 'rh.vinculos.index',
            'mit_ordem' => 3
        ]);

        // Item funcoes
        MenuItem::create([
            'mit_mod_id' => 6,
            'mit_nome' => 'Funções',
            'mit_item_pai' => $rh->mit_id,
            'mit_icone' => 'fa fa-user',
            'mit_rota' => 'rh.funcoes.index',
            'mit_ordem' => 4
        ]);

        // Item setores
        MenuItem::create([
            'mit_mod_id' => 6,
            'mit_nome' => 'Setores',
            'mit_item_pai' => $rh->mit_id,
            'mit_icone' => 'fa fa-users',
            'mit_rota' => 'rh.setores.index',
            'mit_ordem' => 5
        ]);

        // Item períodos laborais
        MenuItem::create([
            'mit_mod_id' => 6,
            'mit_nome' => 'Períodos Laborais',
            'mit_item_pai' => $rh->mit_id,
            'mit_icone' => 'fa fa-calendar',
            'mit_rota' => 'rh.periodoslaborais.index',
            'mit_ordem' => 6
        ]);

        // Item colaboradores
        MenuItem::create([
            'mit_mod_id' => 6,
            'mit_nome' => 'Colaboradores',
            'mit_item_pai' => $rh->mit_id,
            'mit_icone' => 'fa fa-user',
            'mit_rota' => 'rh.colaboradores.index',
            'mit_ordem' => 7
        ]);

        // Item fontes pagadora
        MenuItem::create([
            'mit_mod_id' => 6,
            'mit_nome' => 'Fontes Pagadoras',
            'mit_item_pai' => $rh->mit_id,
            'mit_icone' => 'fa fa-money',
            'mit_rota' => 'rh.fontespagadoras.index',
            'mit_ordem' => 8
        ]);
    }
}
