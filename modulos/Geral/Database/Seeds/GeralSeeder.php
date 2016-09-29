<?php
namespace Modulos\Geral\Database\Seeds;

use Illuminate\Database\Seeder;

class GeralSeeder extends Seeder
{
    public function run()
    {
        $this->call(PessoaTableSeeder::class);
        $this->command->info('Pessoas Table seeded');

        $this->call(TiposAnexoTableSeeder::class);
        $this->command->info('Tipos Anexos Table seeded');
    }
}
