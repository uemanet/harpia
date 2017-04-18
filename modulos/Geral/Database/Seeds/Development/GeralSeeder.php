<?php
namespace Modulos\Geral\Database\Seeds\Development;

use Illuminate\Database\Seeder;

class GeralSeeder extends Seeder
{
    public function run()
    {
        $this->call(TiposAnexoTableSeeder::class);
        $this->command->info('Tipos Anexos Table seeded');

        $this->call(TiposDocumentoSeeder::class);
        $this->command->info('Tipos de Documentos Table seeded');

        $this->call(PessoaTableSeeder::class);
        $this->command->info('Pessoas Table seeded');

        $this->call(TitulacaoTableSeeder::class);
        $this->command->info('Titulacoes Table seeded');
    }
}
