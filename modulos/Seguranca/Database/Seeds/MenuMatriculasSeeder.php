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
        // Criando itens no menu

        // Categoria Matriculas
        $matriculas = MenuItem::create([
            'mit_mod_id' => 7,
            'mit_nome' => 'Matriculas',
            'mit_icone' => 'fa fa-link',
            'mit_ordem' => 1
        ]);

        // Item Dashboard
        MenuItem::create([
            'mit_mod_id' => 7,
            'mit_nome' => 'Dashboard',
            'mit_item_pai' => $matriculas->mit_id,
            'mit_icone' => 'fa fa-tachometer',
            'mit_rota' => 'matriculas.index.index',
            'mit_ordem' => 1
        ]);


    }
}
