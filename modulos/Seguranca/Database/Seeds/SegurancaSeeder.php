<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;
use Configuracao;

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
        $this->command->info('Pessoa / usuario table seeded!');

        $this->call(CategoriaRecursoTableSeeder::class);
        $this->command->info('Categoria recurso table seeded!');

        $this->call(RecursoTableSeeder::class);
        $this->command->info('Recurso table seeded!');

        $this->call(PermissaoTableSeeder::class);
        $this->command->info('Permissao table seeded!');

        $this->call(PerfilTableSeeder::class);
        $this->command->info('Perfil table seeded!');

        $this->call(PerfilPermissaoTableSeeder::class);
        $this->command->info('Perfil permissao table seeded!');

        $this->call(PerfilUsuarioTableSeeder::class);
        $this->command->info('Perfil usuario table seeded!');

        Configuracao::set('time_between_clicks', 1200, 5);
        $this->command->info('Configuracao table seeded!');
    }
}
