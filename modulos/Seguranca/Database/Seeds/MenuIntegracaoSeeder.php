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

      // Categoria Cadastros
      $cadastro = MenuItem::create([
          'mit_mod_id' => 4,
          'mit_nome' => 'Cadastros',
          'mit_icone' => 'fa fa-plus',
          'mit_ordem' => 1
      ]);

        $dashboard = MenuItem::create([
          'mit_mod_id' => 4,
          'mit_item_pai' => $cadastro->mit_id,
          'mit_nome' => 'Ambientes Virtuais',
          'mit_icone' => 'fa fa-laptop',
          'mit_rota' => 'integracao.ambientesvirtuais.index',
          'mit_ordem' => 1
      ]);
    }
}
