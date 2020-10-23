<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;

use Modulos\Seguranca\Models\MenuItem;
use Modulos\Seguranca\Models\Modulo;
use Modulos\Seguranca\Models\Perfil;
use Modulos\Seguranca\Models\Permissao;
use Modulos\Seguranca\Models\Usuario;

class MenuMatriculasSeeder extends Seeder
{
    public function run()
    {

        $modulo = Modulo::where('mod_slug','matriculas')->first();

        // Criando itens no menu
        // Categoria Matriculas
        $matriculas = MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_nome' => 'Matriculas',
            'mit_icone' => 'fa fa-link',
            'mit_ordem' => 1
        ]);

        // Item Dashboard
        MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_nome' => 'Seletivos',
            'mit_item_pai' => $matriculas->mit_id,
            'mit_icone' => 'fa fa-tachometer',
            'mit_rota' => 'matriculas.index.index',
            'mit_ordem' => 1
        ]);

        // Item Chamadas
        MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_nome' => 'Chamadas',
            'mit_item_pai' => $matriculas->mit_id,
            'mit_icone' => 'fa fa-list-alt',
            'mit_rota' => 'matriculas.chamadas.index',
            'mit_ordem' => 2
        ]);


    }
}