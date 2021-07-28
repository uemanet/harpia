<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;

use Modulos\Seguranca\Models\MenuItem;
use Modulos\Seguranca\Models\Modulo;
use Modulos\Seguranca\Models\Perfil;
use Modulos\Seguranca\Models\Permissao;
use Modulos\Seguranca\Models\Usuario;

class MenuAlunosSeeder extends Seeder
{
    public function run()
    {
        $modulo = Modulo::where('mod_slug','alunos')->first();
        // Categoria Portal do Aluno
        $matriculas = MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_nome' => 'Portal do Aluno',
            'mit_icone' => 'fa fa-link',
            'mit_ordem' => 1
        ]);

        // Item Dashboard
        MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_nome' => 'Dashboard',
            'mit_item_pai' => $matriculas->mit_id,
            'mit_icone' => 'fa fa-tachometer',
            'mit_rota' => 'alunos.index.index',
            'mit_ordem' => 1
        ]);
    }
}