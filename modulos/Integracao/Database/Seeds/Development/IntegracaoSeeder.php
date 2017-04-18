<?php
namespace Modulos\Integracao\Database\Seeds\Development;

use Illuminate\Database\Seeder;

class IntegracaoSeeder extends Seeder
{
    public function run()
    {
        $this->call(ServicoTableSeeder::class);
        $this->command->info('Servicos Table seeded');
    }
}
