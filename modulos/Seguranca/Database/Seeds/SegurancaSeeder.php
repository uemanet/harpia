<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;

class SegurancaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ModuloTableSeeder::class);
        $this->command->info('Modulos table seeded!');

        $this->call(UsuarioTableSeeder::class);
        $this->command->info('Pessoa / Usuário table seeded!');
    }
}