<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;

use Modulos\Seguranca\Models\MenuItem;
use Modulos\Seguranca\Models\Modulo;
use Modulos\Seguranca\Models\Perfil;
use Modulos\Seguranca\Models\Permissao;
use Modulos\Seguranca\Models\Usuario;

class MenuSegurancaSeeder extends Seeder
{
    public function run()
    {
        // Criando itens no menu

        // Categoria Monitoramento
        $seguranca = MenuItem::create([
            'mit_mod_id' => 1,
            'mit_nome' => 'Segurança',
            'mit_icone' => 'fa fa-lock',
            'mit_ordem' => 1
        ]);

        // Item Dashboard
        MenuItem::create([
            'mit_mod_id' => 1,
            'mit_nome' => 'Dashboard',
            'mit_item_pai' => $seguranca->mit_id,
            'mit_icone' => 'fa fa-tachometer',
            'mit_rota' => 'seguranca.index.index',
            'mit_ordem' => 1
        ]);

        // Subcategoria Cadastros
        $cadastros = MenuItem::create([
            'mit_mod_id' => 1,
            'mit_nome' => 'Cadastros',
            'mit_item_pai' => $seguranca->mit_id,
            'mit_icone' => 'fa fa-plus',
            'mit_ordem' => 2
        ]);

        // Itens de cadastros
        MenuItem::create([
            'mit_mod_id' => 1,
            'mit_item_pai' => $cadastros->mit_id,
            'mit_nome' => 'Perfis',
            'mit_icone' => 'fa fa-user-secret',
            'mit_rota' => 'seguranca.perfis.index',
            'mit_ordem' => 1
        ]);

        MenuItem::create([
            'mit_mod_id' => 1,
            'mit_item_pai' => $cadastros->mit_id,
            'mit_nome' => 'Usuarios',
            'mit_icone' => 'fa fa-users',
            'mit_rota' => 'seguranca.usuarios.index',
            'mit_ordem' => 2
        ]);

        MenuItem::create([
            'mit_mod_id' => 1,
            'mit_item_pai' => $cadastros->mit_id,
            'mit_nome' => 'Permissões',
            'mit_icone' => 'fa fa-unlock',
            'mit_rota' => 'seguranca.permissoes.index',
            'mit_ordem' => 3
        ]);
    }
}
