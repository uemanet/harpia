<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;

use Modulos\Seguranca\Models\MenuItem;
use Modulos\Seguranca\Models\Modulo;
use Modulos\Seguranca\Models\Perfil;
use Modulos\Seguranca\Models\Permissao;
use Modulos\Seguranca\Models\Usuario;

class ModulosTableSeeder extends Seeder
{
    public function run()
    {

        // Modulo Segurança
        $modulo = Modulo::create([ //Id 1
          'mod_nome' => 'Segurança',
          'mod_slug' => 'seguranca',
          'mod_icone' => 'fa fa-lock',
          'mod_classes' => 'bg-red'
        ]);

        // Modulo Geral
        $modulo = Modulo::create([ //Id 2
            'mod_nome' => 'Geral',
            'mod_slug' => 'geral',
            'mod_icone' => 'fa fa-cubes',
            'mod_classes' => 'bg-blue'
        ]);

        // Modulo Acadêmico
        $modulo = Modulo::create([ //Id 3
            'mod_nome' => 'Acadêmico',
            'mod_slug' => 'academico',
            'mod_icone' => 'fa fa-university',
            'mod_classes' => 'bg-green'
        ]);

        // Modulo Integração
        $modulo = Modulo::create([ //Id 4
            'mod_nome' => 'Integração',
            'mod_slug' => 'integracao',
            'mod_icone' => 'fa fa-cogs',
            'mod_classes' => 'bg-aqua'
        ]);
        
        // Modulo Monitoramento
        $modulo = Modulo::create([ //Id 5
            'mod_nome' => 'Monitoramento',
            'mod_slug' => 'monitoramento',
            'mod_icone' => 'fa fa-line-chart',
            'mod_classes' => 'bg-yellow'
        ]);
    }
}
