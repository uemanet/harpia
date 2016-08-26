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

        $this->call(ProfessorTableSeed::class);
        $this->command->info('Professor Table seeded!');

        $this->call(CentroTableSeeder::class);
        $this->command->info('Centro Table seeded!');

        $this->call(DepartamentoTableSeeder::class);
        $this->command->info('Departamento Table seeded!');

        $this->call(PoloTableSeeder::class);
        $this->command->info('Polo Table seeded!');
    }
}
