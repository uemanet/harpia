<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;

use Modulos\Seguranca\Models\MenuItem;
use Modulos\Seguranca\Models\Modulo;
use Modulos\Seguranca\Models\Perfil;
use Modulos\Seguranca\Models\Permissao;
use Modulos\Seguranca\Models\Usuario;

class MenuMonitoramentoSeeder extends Seeder
{
    public function run()
    {
        // Criando itens no menu

        // Categoria Monitoramento
        $monitoramento = MenuItem::create([
            'mit_mod_id' => 5,
            'mit_nome' => 'Monitoramento',
            'mit_icone' => 'fa fa-tachometer',
            'mit_ordem' => 1
        ]);

        $dashboard = MenuItem::create([
            'mit_mod_id' => 5,
            'mit_item_pai' => $monitoramento->mit_id,
            'mit_nome' => 'Dashboard',
            'mit_icone' => 'fa fa-dashboard',
            'mit_rota' => 'monitoramento.index.index',
            'mit_ordem' => 1
        ]);

        $tempoOnline = MenuItem::create([
            'mit_mod_id' => 5,
            'mit_item_pai' => $monitoramento->mit_id,
            'mit_nome' => 'Tempo Online',
            'mit_icone' => 'fa fa-bar-chart',
            'mit_rota' => 'monitoramento.tempoonline.index',
            'mit_ordem' => 2
        ]);

        $respostasForuns = MenuItem::create([
            'mit_mod_id' => 5,
            'mit_item_pai' => $monitoramento->mit_id,
            'mit_nome' => 'Respostas a Fóruns',
            'mit_icone' => 'fa fa-envelope-open',
            'mit_rota' => 'monitoramento.forumresponse.index',
            'mit_ordem' => 3
        ]);
    }
}
