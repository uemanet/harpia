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
            'mit_ordem' => 1
        ]);

    }
}
