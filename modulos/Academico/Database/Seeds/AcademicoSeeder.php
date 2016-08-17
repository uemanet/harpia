<?php

namespace Modulos\Academico\Database\Seeds;

use Illuminate\Database\Seeder;

class AcademicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ModalidadeTableSeeder::class);
        $this->command->info('Modalidades table seeded!');

        $this->call(NivelCursoTableSeeder::class);
        $this->command->info('NiveisCursos table seeded!');
    }
}
