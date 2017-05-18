<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;

use Modulos\Seguranca\Models\MenuItem;
use Modulos\Seguranca\Models\Modulo;
use Modulos\Seguranca\Models\Perfil;
use Modulos\Seguranca\Models\Permissao;
use Modulos\Seguranca\Models\Usuario;

class SegurancaSeeder extends Seeder
{
    public function run()
    {
        $this->call(UsuarioTableSeeder::class);

        $this->call(ModulosTableSeeder::class);

        $this->call(MenuSegurancaSeeder::class);

        $this->call(MenuGeralSeeder::class);

        $this->call(MenuAcademicoSeeder::class);

        $this->call(MenuIntegracaoSeeder::class);

        $this->call(MenuMonitoramentoSeeder::class);

        $this->call(PermissoesSegurancaSeeder::class);

        $this->call(PermissoesGeralSeeder::class);

        $this->call(PermissoesAcademicoSeeder::class);

        $this->call(PermissoesIntegracaoSeeder::class);

        $this->call(PermissoesMonitoramentoSeeder::class);
    }
}
