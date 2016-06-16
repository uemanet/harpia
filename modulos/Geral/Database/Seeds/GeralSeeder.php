<?php

namespace Modulos\Geral\Database\Seeds;

use Illuminate\Database\Seeder;

class GeralSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(TipoDocumentoTableSeeder::class);
        $this->command->info('Tipo Documento table seeded!');
    }
}
