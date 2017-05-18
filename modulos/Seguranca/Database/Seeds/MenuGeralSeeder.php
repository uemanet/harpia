<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;

use Modulos\Seguranca\Models\MenuItem;
use Modulos\Seguranca\Models\Modulo;
use Modulos\Seguranca\Models\Perfil;
use Modulos\Seguranca\Models\Permissao;
use Modulos\Seguranca\Models\Usuario;

class MenuGeralSeeder extends Seeder
{
    public function run()
    {
        // Criando itens no menu

        // Categoria Geral
        $geral = MenuItem::create([
            'mit_mod_id' => 2,
            'mit_nome' => 'Geral',
            'mit_icone' => 'fa fa-reorder',
            'mit_ordem' => 1
        ]);

        // Item Dashboard
        $dashboard = MenuItem::create([
            'mit_mod_id' => 2,
            'mit_item_pai' => $geral->mit_id,
            'mit_nome' => 'Dashboard',
            'mit_icone' => 'fa fa-tachometer',
            'mit_rota' => 'geral.index.index',
            'mit_ordem' => 1
        ]);

        // Subcategoria Cadastros
        $cadastro = MenuItem::create([
            'mit_mod_id' => 2,
            'mit_item_pai' => $geral->mit_id,
            'mit_nome' => 'Cadastros',
            'mit_icone' => 'fa fa-plus',
            'mit_ordem' => 2
        ]);

        $pessoas = MenuItem::create([
            'mit_mod_id' => 2,
            'mit_item_pai' => $cadastro->mit_id,
            'mit_nome' => 'Pessoas',
            'mit_icone' => 'fa fa-user',
            'mit_rota' => 'geral.pessoas.index',
            'mit_ordem' => 1
        ]);

        $titulacoes = MenuItem::create([
            'mit_mod_id' => 2,
            'mit_item_pai' => $cadastro->mit_id,
            'mit_nome' => 'Titulações',
            'mit_rota' => 'geral.titulacoes.index',
            'mit_icone' => 'fa fa-file-text-o',
            'mit_ordem' => 2
        ]);
    }
}
